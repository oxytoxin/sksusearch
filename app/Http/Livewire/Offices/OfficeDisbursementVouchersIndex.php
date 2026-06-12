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
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Jobs\SendSmsJob;

class OfficeDisbursementVouchersIndex extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    public $tracking_num_from_scan;


    public function updated($name, $value)
    {
        if ($name == 'tracking_num_from_scan') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                $route = Route::getRoutes()->match(Request::create($value));
                if ($route->getName() == 'disbursement-vouchers.show-from-trn') {
                    $value = $route->parameters['disbursement_voucher'];
                }
            }
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
                $this->tracking_num_from_scan = null;
            }
        }
    }

    protected function getTableQuery()
    {
        return DisbursementVoucher::whereRelation('current_step', 'office_group_id', '=', auth()->user()->employee_information->office->office_group_id)->oldest('current_step_id')->latest();
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('for_cancellation')->options([
                true => 'For Cancellation',
                false => 'For Approval',
            ])->default(0)->label('Status'),
            // Accounting-internal phase (item 2.2 / 2.3). Pre-Audit and the
            // verification queue split into "For JEV" (verification/recording,
            // steps 10000-12000) and "For Certification" (Chief Accountant, 13000).
            SelectFilter::make('phase')
                ->options([
                    'pre_audit' => 'For Pre-Audit',
                    'for_jev' => 'For JEV',
                    'for_certification' => 'For Certification',
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['value'], function (Builder $query, string $value) {
                        if ($value === 'pre_audit') {
                            $query->whereIn('current_step_id', [5000, 6000]);
                        } elseif ($value === 'for_jev') {
                            $query->whereIn('current_step_id', [10000, 11000, 12000]);
                        } elseif ($value === 'for_certification') {
                            $query->where('current_step_id', 13000);
                        }
                    });
                })
                ->label('Accounting Phase')
                ->visible(fn () => auth()->user()->employee_information->office->office_group_id == 2),
            // Incoming/Outgoing queue (item 2.1) — works for every office, keyed off
            // the DV's current step process and the pending-return flag.
            SelectFilter::make('queue')
                ->options([
                    'incoming' => 'Incoming — to receive',
                    'in_office' => 'In office — received',
                    'for_release' => 'For release / returned',
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['value'], function (Builder $query, string $value) {
                        if ($value === 'incoming') {
                            $query->whereHas('current_step', fn ($q) => $q->where('process', 'Forwarded to'))
                                ->whereNull('pending_return_step_id');
                        } elseif ($value === 'in_office') {
                            $query->whereHas('current_step', fn ($q) => $q->whereIn('process', ['Received in', 'Received by']))
                                ->whereNull('pending_return_step_id');
                        } elseif ($value === 'for_release') {
                            $query->whereNotNull('pending_return_step_id');
                        }
                    });
                })
                ->label('Queue'),
            Filter::make('created_at')
                ->form([
                    Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('from'),
                            Forms\Components\DatePicker::make('until'),
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
                            fn(Builder $query, $date): Builder => $query->whereDate('submitted_at', '>=', $date),
                        )
                        ->when(
                            $data['until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('submitted_at', '<=', $date),
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
            ...$this->icuVerifyAction(),
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
                ->visible(fn($record) => $record->current_step_id == 13000 && $record->for_cancellation == false && !$record->certified_by_accountant && auth()->user()->employee_information->position_id == auth()->user()->employee_information->office->head_position_id && blank($record->pending_return_step_id))
                ->requiresConfirmation(),
            ...$this->adjustmentActions(),
            ...$this->icuReturnAction(),
            ...$this->releaseAction(),
            Action::make('return')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $destinationStep = DisbursementVoucherStep::find($data['return_step_id']);
                $record->update([
                    'pending_return_step_id' => $data['return_step_id'],
                ]);
                $record->activity_logs()->create([
                    'description' => 'DV marked for return to ' . ($destinationStep->recipient ?? 'Unknown') . '. Awaiting physical release.',
                    'remarks' => $data['remarks'] ?? null,
                ]);
                DB::commit();

                // ========== SMS NOTIFICATION ==========
                $record->load(['user.employee_information']);
                $trackingNumber = $record->tracking_number;
                $officerName = auth()->user()->employee_information->full_name ?? 'Officer';
                $remarks = $data['remarks'] ?? 'No remarks provided';

                // Strip HTML tags and decode HTML entities from remarks
                $remarks = strip_tags($remarks);
                $remarks = html_entity_decode($remarks, ENT_QUOTES, 'UTF-8');

                $message = "Your DV with ref. no. {$trackingNumber} has been returned by {$officerName} with the following remarks: \"{$remarks}\". Please retrieve your documents immediately.";

                // Send to the user who requested the disbursement voucher
                $requestedBy = $record->user;
                if ($requestedBy && $requestedBy->employee_information && !empty($requestedBy->employee_information->contact_number)) {
                    SendSmsJob::dispatch(
                        $requestedBy->employee_information->contact_number,
                        $message,
                        'disbursement_voucher_returned',
                        $requestedBy->id,
                        auth()->id()
                    );
                }
                // ========== SMS NOTIFICATION END ==========

                // ========== EMAIL NOTIFICATION (PREPARED, DISABLED) ==========
                // No live email provider / verified domain yet. The full sender is
                // built and ready in dispatchReturnEmail(); re-enable by uncommenting
                // the line below once email is set up.
                // $this->dispatchReturnEmail($record, $officerName, $remarks);
                // ========== EMAIL NOTIFICATION END ==========

                Notification::make()->title('DV marked for return. Use "Release Document" when the hardcopy is picked up.')->success()->send();
            })
                ->color('danger')
                ->visible(fn($record) => $record->current_step->process != 'Forwarded to' && $record->for_cancellation == false && $record->current_step_id != 6000 && blank($record->pending_return_step_id))
                ->form(function () {
                    return [
                        Select::make('return_step_id')
                            ->label('Return to')
                            ->options(fn($record) => DisbursementVoucherStep::where('process', 'Forwarded to')->where('recipient', '!=', $record->current_step->recipient)->where('id', '<', $record->current_step_id)->pluck('recipient', 'id'))
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
                $next_step = $process_ids->last(fn($value) => $value < $record->current_step->first_step_in_group->id);
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
