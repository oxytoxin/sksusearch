<?php

namespace App\Http\Livewire\Oic;

use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Components\Select;
use App\Models\DisbursementVoucherStep;
use App\Models\OicUser;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;

class OicOfficeDisbursementVouchers extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    public function isOic()
    {
        return true;
    }

    protected function getTableQuery()
    {
        return DisbursementVoucher::where('current_step_id', '>', 4000)->latest('submitted_at');
    }

    public function getTableColumns()
    {
        return [
            ...$this->officeTableColumns()
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('as')
                ->searchable()
                ->placeholder('Select User')
                ->options(EmployeeInformation::whereIn('user_id', OicUser::valid()->distinct('user_id')->pluck('user_id'))->pluck('full_name', 'user_id'))
                ->query(function ($query, $state) {
                    $query->whereRelation('current_step', 'office_group_id', '=', User::find($state)->first()?->employee_information->office->office_group_id ?? -777);
                }),
            SelectFilter::make('for_cancellation')->options([
                true => 'For Cancellation',
                false => 'For Approval',
            ])->default(0)->label('Status'),
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
                $description = 'Disbursement voucher certified.';
                if ($this->isOic()) {
                    $description .= "\nOIC: " . auth()->user()->employee_information->full_name . '.';
                }
                $record->activity_logs()->create([
                    'description' => $description,
                ]);
                DB::commit();
                Notification::make()->title('Disbursement voucher certified.')->success()->send();
            })
                ->visible(fn ($record, $livewire) => $record->current_step_id == 13000 && $record->for_cancellation == false && !$record->certified_by_accountant && User::find($livewire->tableFilters['as']['value'])?->employee_information->position_id == User::find($livewire->tableFilters['as']['value'])?->employee_information->office->head_position_id)
                ->requiresConfirmation(),
            Action::make('return')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                if ($record->current_step_id < $record->previous_step_id) {
                    $previous_step_id = $record->previous_step_id;
                } else {
                    $previous_step_id = DisbursementVoucherStep::where('process', 'Forwarded to')->where('recipient', $record->current_step->recipient)->first()->id;
                }
                $record->update([
                    'current_step_id' => $data['return_step_id'],
                    'previous_step_id' => $previous_step_id,
                ]);
                $record->refresh();
                $description = 'Disbursement Voucher returned to ' . $record->current_step->recipient . '.';
                if ($this->isOic()) {
                    $description .= "\nOIC: " . auth()->user()->employee_information->full_name . '.';
                }
                $record->activity_logs()->create([
                    'description' => $description,
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
                $next_step = $process_ids->last(fn ($value) => $value < EmployeeInformation::firstWhere('user_id', $this->tableFilters['as']['value'])->office->office_group->disbursement_voucher_starting_step->id);
                $record->update([
                    'current_step_id' => $next_step,
                ]);
                $record->activity_logs()->create([
                    'description' => 'Cancellation approved by OIC:' . auth()->user()->employee_information->full_name,
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

    protected function getTableFiltersFormColumns(): int
    {
        return 2;
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    public function render()
    {
        return view('livewire.oic.oic-office-disbursement-vouchers');
    }
}
