<?php

namespace App\Http\Livewire\Offices;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\DisbursementVoucher;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Components\Select;
use App\Models\DisbursementVoucherStep;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Grid;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use Carbon\Carbon;

class OfficeDisbursementVouchersIndex extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    public $tracking_num_from_scan;


    public function updated($name, $value)
    {
        if ($name == 'tracking_num_from_scan') {
            $dv = DisbursementVoucher::where("tracking_number", '=', $value)->whereRelation('current_step', 'process', '=', "Forwarded to")->whereRelation('current_step', 'office_group_id', '=', auth()->user()->employee_information->office->office_group_id)->first();
            if ($dv != null) {
                DB::beginTransaction();

                $dv->update([
                    'current_step_id' => $dv->current_step->next_step->id,
                ]);
                $dv->refresh();
                $description = $dv->current_step->process . ' ' . $dv->current_step->recipient . ' by ';
                if ($this->isOic()) {
                    $description .= "OIC: " . auth()->user()->employee_information->full_name . '.';
                } else {
                    $description .= auth()->user()->employee_information->full_name;
                }
                $dv->activity_logs()->create([
                    'description' => $description,
                ]);
                if ($dv->current_step_id == 8000 || $dv->current_step_id == 11000) {
                    $dv->update([
                        'current_step_id' => $dv->current_step_id + 1000,
                    ]);
                    $dv->refresh();
                    $dv->activity_logs()->create([
                        'description' => $dv->current_step->process,
                    ]);
                }
                DB::commit();
                Notification::make()->title('Document Received')->success()->send();
                redirect()->route('office.dashboard');
            } else {
                Notification::make()->title('Document Not Found or Already Received')->warning()->send();
                redirect()->route('office.dashboard');
            }
        }
    }

    protected function getTableQuery()
    {
        return DisbursementVoucher::whereRelation('current_step', 'office_group_id', '=', auth()->user()->employee_information->office->office_group_id)->latest();
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('for_cancellation')->options([
                true => 'For Cancellation',
                false => 'For Approval',
            ])->default(0)->label('Status'),
            Filter::make('created_at')
                ->form([
                    Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('from')->default(now()),
                            Forms\Components\DatePicker::make('until')->default(now()),
                        ])
                ])
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if (date_create($data['from']) == date_create($data['until']) && date_create($data['from']) == date_create(now())) {
                        $indicators['from'] = 'Today';
                    } else {
                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'Created from ' . Carbon::parse($data['from'])->toFormattedDateString();
                        }
                        if ($data['until'] ?? null) {
                            $indicators['until'] = 'Created until ' . Carbon::parse($data['until'])->toFormattedDateString();
                        }
                    }
                    return $indicators;
                })
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('submitted_at', '>=', $date),
                        )
                        ->when(
                            $data['until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('submitted_at', '<=', $date),
                        );
                })
        ];
    }


    protected function getTableFiltersFormColumns(): int
    {
        return 2;
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getTableColumns()
    {
        return [
            ...$this->officeTableColumns()
        ];
    }

    protected function getTableActions()
    {
        return [
            ...$this->commonActions(),
            ...$this->budgetOfficeActions(),
            ...$this->accountingActions(),
            ...$this->cashierActions(),
            ...$this->icuActions(),
            Action::make('certify')->button()->action(function ($record) {
                DB::beginTransaction();
                $record->update([
                    'certified_by_accountant' => true,
                ]);
                $record->activity_logs()->create([
                    'description' => 'Disbursement voucher certified.',
                ]);
                DB::commit();
                Notification::make()->title('Disbursement voucher certified.')->success()->send();
            })
                ->visible(fn ($record) => $record->current_step_id == 13000 && $record->for_cancellation == false && !$record->certified_by_accountant && auth()->user()->employee_information->position_id == 12)
                ->requiresConfirmation(),
            Action::make('return')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                if ($record->current_step_id < $record->previous_step_id) {
                    $previous_step_id = $record->previous_step_id;
                } else {
                    $previous_step_id = DisbursementVoucherStep::where('process', 'Forwarded to')->where('id', '<', $record->current_step->id)->latest('id')->first()->id;
                }
                $record->update([
                    'current_step_id' => $data['return_step_id'],
                    'previous_step_id' => $previous_step_id,
                ]);
                $record->refresh();
                $record->activity_logs()->create([
                    'description' => 'Disbursement Voucher returned to ' . $record->current_step->recipient,
                    'remarks' => $data['remarks'] ?? null,
                ]);
                DB::commit();
                Notification::make()->title('Disbursement Voucher returned.')->success()->send();
            })
                ->color('danger')
                ->visible(fn ($record) => $record->current_step->process != 'Forwarded to' && $record->for_cancellation == false)
                ->form(function () {
                    return [
                        Select::make('return_step_id')
                            ->label('Return to')
                            ->options(fn ($record) => DisbursementVoucherStep::where('process', 'Forwarded to')->where('recipient', '!=', $record->current_step->recipient)->where('id', '<', $record->current_step_id)->pluck('recipient', 'id'))
                            ->required(),
                        RichEditor::make('remarks')
                            ->label('Remarks (Optional)')
                            ->fileAttachmentsDisk('remarks'),
                    ];
                })
                ->modalWidth('4xl')
                ->requiresConfirmation(),
            Action::make('Cancel')->action(function ($record) {
                DB::beginTransaction();
                $process_ids = DisbursementVoucherStep::where('process', 'Received by')->orWhere('process', 'Received in')->pluck('id');
                $next_step = $process_ids->last(fn ($value) => $value < auth()->user()->employee_information->office->office_group->disbursement_voucher_starting_step->id);
                $record->update([
                    'current_step_id' => $next_step,
                ]);
                $record->activity_logs()->create([
                    'description' => 'Cancellation approved by ' . auth()->user()->employee_information->full_name,
                ]);
                DB::commit();
                Notification::make()->title('Disbursement voucher approved for cancellation.')->success()->send();
                return;
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->for_cancellation && !$record->cancelled_at;
                })
                ->requiresConfirmation()
                ->button()
                ->color('danger'),
            ...$this->viewActions(),

        ];
    }

    public function render()
    {
        return view('livewire.offices.office-disbursement-vouchers-index');
    }
}
