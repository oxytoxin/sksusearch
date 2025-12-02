<?php

namespace App\Http\Livewire\Offices;

use Livewire\Component;
use App\Models\LiquidationReport;
use Illuminate\Support\Facades\DB;
use App\Forms\Components\Flatpickr;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Layout;
use App\Models\LiquidationReportStep;
use Filament\Forms\Components\Select;
use App\Models\DisbursementVoucherStep;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Jobs\SendSmsJob;

class OfficeLiquidationReportsIndex extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return LiquidationReport::whereRelation('current_step', 'office_group_id', '=', auth()->user()->employee_information->office->office_group_id)->latest('report_date');
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('for_cancellation')->options([
                true => 'For Cancellation',
                false => 'For Approval',
            ])->default(0)->label('Status'),
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }


    protected function getTableColumns()
    {
        return [
            TextColumn::make('tracking_number'),
            TextColumn::make('disbursement_voucher.tracking_number'),
            TextColumn::make('report_date')->date()->label('Date'),
        ];
    }

    protected function getTableActions()
    {
        return [
            Action::make('Receive')->button()->action(function ($record) {
                if ($record->current_step->process == 'Forwarded to') {
                    DB::beginTransaction();
                    $record->update([
                        'current_step_id' => $record->current_step->next_step->id,
                    ]);
                    $record->refresh();
                    $record->activity_logs()->create([
                        'description' => $record->current_step->process . ' ' . auth()->user()->employee_information->full_name,
                    ]);

                    if ($record->current_step_id == 6000) {
                        $record->update([
                            'current_step_id' => $record->current_step->next_step->id,
                        ]);
                        $record->refresh();
                        $record->activity_logs()->create([
                            'description' => $record->current_step->process,
                        ]);
                    }

                    DB::commit();
                    Notification::make()->title('Document Received')->success()->send();
                }
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found.')->warning()->send();
                        return false;
                    }
                    return $record->current_step->process == 'Forwarded to' && $record->for_cancellation == false;
                })
                ->requiresConfirmation(),
            Action::make('Forward')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                if ($record->current_step_id >= ($record->previous_step_id ?? 0)) {
                    $record->update([
                        'current_step_id' => $record->current_step->next_step->id,
                    ]);
                } else {
                    $record->update([
                        'current_step_id' => $record->previous_step_id,
                    ]);
                }
                $record->refresh();
                $record->activity_logs()->create([
                    'description' => $record->current_step->process . ' ' . $record->current_step->recipient . ' by ' . auth()->user()->employee_information->full_name,
                    'remarks' => $data['remarks'] ?? null,
                ]);
                DB::commit();
                Notification::make()->title('Document Forwarded')->success()->send();
            })
                ->form(function () {
                    return [
                        RichEditor::make('remarks')
                            ->label('Remarks (Optional)')
                            ->fileAttachmentsDisk('remarks'),
                    ];
                })
                ->modalWidth('4xl')
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->certified_by_accountant && !$record->for_cancellation;
                })
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

                // ========== SMS NOTIFICATION ==========
                $record->load(['disbursement_voucher.user.employee_information']);
                $trackingNumber = $record->tracking_number;
                $officerName = auth()->user()->employee_information->full_name ?? 'Officer';
                $remarks = $data['remarks'] ?? 'No remarks provided';

                // Strip HTML tags and decode HTML entities from remarks
                $remarks = strip_tags($remarks);
                $remarks = html_entity_decode($remarks, ENT_QUOTES, 'UTF-8');

                $message = "Your LR with ref. no. {$trackingNumber} has been returned by {$officerName} with the following remarks: \"{$remarks}\". Please retrieve your documents immediately.";

                // Send to the user who requested the disbursement voucher
                $requestedBy = $record->disbursement_voucher->user;
                if ($requestedBy && $requestedBy->employee_information && !empty($requestedBy->employee_information->contact_number)) {
                    SendSmsJob::dispatch(
                        '09273464891',  // TEST PHONE - Remove this line for production
                        // $requestedBy->employee_information->contact_number,  // PRODUCTION - Uncomment this
                        $message,
                        'liquidation_report_returned',
                        $requestedBy->id,
                        auth()->id()
                    );
                }
                // ========== SMS NOTIFICATION END ==========

                Notification::make()->title('Disbursement Voucher returned.')->success()->send();
            })
                ->color('danger')
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 4000 && $record->for_cancellation == false;
                })
                ->form(function () {
                    return [
                        Select::make('return_step_id')
                            ->label('Return to')
                            ->options(fn ($record) => LiquidationReportStep::where('process', 'Forwarded to')->where('recipient', '!=', $record->current_step->recipient)->where('id', '<', $record->current_step_id)->pluck('recipient', 'id'))
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
                $record->update([
                    'cancelled_at' => now(),
                ]);
                $record->activity_logs()->create([
                    'description' => 'Cancellation approved.',
                ]);
                DB::commit();
                Notification::make()->title('Liquidation Report approved for cancellation.')->success()->send();
                return;
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 4000 && $record->for_cancellation && !$record->cancelled_at;
                })
                ->requiresConfirmation()
                ->button()
                ->color('danger'),
            Action::make('verify')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $record->update([
                    'lr_number' => $data['lr_number'],
                    'journal_date' => $data['journal_date'],
                    'related_documents' => [
                        'required_documents' => $record?->disbursement_voucher->voucher_subtype->related_documents_list?->liquidation_report_documents ?? [],
                        'verified_documents' => $data['verified_documents'],
                        'remarks' => $data['remarks'] ?? '',
                    ],
                    'current_step_id' => $record->current_step->next_step->id,
                ]);
                $record->refresh();
                $description = 'Liquidation Report verified.';

                $record->activity_logs()->create([
                    'description' => $description,
                ]);
                DB::commit();
                Notification::make()->title('Liquidation Report verified.')->success()->send();
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 7000 && blank($record->journal_date) && blank($record->lr_number) && !$record->for_cancellation;
                })
                ->form(function () {
                    return [
                        TextInput::make('lr_number')
                            ->label('LR Number')
                            ->required(),
                        Flatpickr::make('journal_date')
                            ->disableTime()
                            ->required(),
                        Fieldset::make('Related Documents')
                            ->columns(1)
                            ->schema([
                                CheckboxList::make('verified_documents')
                                    ->options(function ($record) {
                                        return collect($record?->disbursement_voucher->voucher_subtype->related_documents_list?->liquidation_report_documents)->flatMap(fn ($d) => [$d => $d]) ?? [];
                                    }),
                                RichEditor::make('remarks')
                            ])
                    ];
                })
                ->modalWidth('3xl')
                ->requiresConfirmation(),
            Action::make('certify')->button()->action(function ($record) {
                DB::beginTransaction();
                $record->update([
                    'certified_by_accountant' => true,
                ]);
                $record->activity_logs()->create([
                    'description' => 'Liquidation Report certified.',
                ]);
                DB::commit();

                // ========== SMS NOTIFICATION ==========
                $record->load(['disbursement_voucher.user.employee_information']);
                $trackingNumber = $record->tracking_number;
                $message = "Your LR with ref. no. {$trackingNumber} has been approved.";

                // Send to the user who requested the disbursement voucher
                $requestedBy = $record->disbursement_voucher->user;
                if ($requestedBy && $requestedBy->employee_information && !empty($requestedBy->employee_information->contact_number)) {
                    SendSmsJob::dispatch(
                        '09273464891',  // TEST PHONE - Remove this line for production
                        // $requestedBy->employee_information->contact_number,  // PRODUCTION - Uncomment this
                        $message,
                        'liquidation_report_approved',
                        $requestedBy->id,
                        auth()->id()
                    );
                }
                // ========== SMS NOTIFICATION END ==========

                Notification::make()->title('Liquidation Report certified.')->success()->send();
            })
                ->visible(fn ($record) => $record->current_step_id == 8000 && !$record->for_cancellation && !$record->certified_by_accountant && auth()->user()->employee_information->position_id == auth()->user()->employee_information->office->head_position_id)
                ->requiresConfirmation(),
            ActionGroup::make([
                ViewAction::make('progress')
                    ->label('Progress')
                    ->icon('ri-loader-4-fill')
                    ->modalHeading('Liquidation Report Progress')
                    ->modalContent(fn ($record) => view('components.timeline_views.progress_logs', [
                        'record' => $record,
                        'steps' => LiquidationReportStep::whereEnabled(true)->where('id', '>', 2000)->get(),
                    ])),
                ViewAction::make('logs')
                    ->label('Activity Timeline')
                    ->icon('ri-list-check-2')
                    ->modalHeading('Liquidation Report Activity Timeline')
                    ->modalContent(fn ($record) => view('components.timeline_views.activity_logs', [
                        'record' => $record,
                    ])),
                ViewAction::make('related_documents')
                    ->label('Related Documents')
                    ->icon('ri-file-copy-2-line')
                    ->modalHeading('Liquidation Report Related Documents')
                    ->modalContent(fn ($record) => view('components.liquidation_reports.liquidation-report-verified-documents', [
                        'liquidation_report' => $record,
                    ])),
                ViewAction::make('view')
                    ->label('Preview')
                    ->openUrlInNewTab()
                    ->url(fn ($record) => route('signatory.liquidation-reports.show', ['liquidation_report' => $record]), true),
            ])->icon('ri-eye-line'),
        ];
    }

    public function mount()
    {
        if (!in_array(auth()->user()->employee_information?->office->office_group_id, [2])) {
            abort(403, 'You are not allowed to access this page.');
        }
    }

    public function render()
    {
        return view('livewire.offices.office-liquidation-reports-index');
    }
}
