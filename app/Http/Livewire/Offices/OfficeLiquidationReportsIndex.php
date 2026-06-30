<?php

namespace App\Http\Livewire\Offices;

use App\Forms\Components\Flatpickr;
use App\Forms\Components\RelatedDocumentsChecklist;
use App\Http\Controllers\NotificationController;
use App\Jobs\SendSmsJob;
use App\Models\LiquidationReport;
use App\Models\LiquidationReportStep;
use App\Models\VoucherSubType;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class OfficeLiquidationReportsIndex extends Component implements HasTable
{
    use InteractsWithTable;

    public bool $returnCameFromVerify = false;

    /**
     * Swap the currently mounted table action to a different one, forcing Filament to
     * rebuild the cached form so the new action's schema is used (a plain
     * mountTableAction() leaves the stale cached form in place).
     */
    protected function swapMountedTableAction(string $actionName, string $recordId)
    {
        if (property_exists($this, 'cachedForms') && is_array($this->cachedForms)) {
            unset($this->cachedForms['mountedTableActionForm']);
        }
        $this->mountedTableActionData = [];

        $this->mountTableAction($actionName, $recordId);
    }

    /**
     * In-modal "Return Document" button on the verify pop-up: swap the same modal from
     * the verify form to the Return form. Nothing is persisted to the LR — the verifier
     * is bouncing it back; the reason captured next goes into the activity log.
     */
    public function openReturnFromVerify($recordId)
    {
        $record = LiquidationReport::find($recordId);
        if (! $record) {
            Notification::make()->title('Liquidation Report not found.')->danger()->send();

            return;
        }

        $this->returnCameFromVerify = true;
        $this->swapMountedTableAction('returnFromAccounting', (string) $recordId);
    }

    /**
     * Smart Cancel for the Return modal: if the user reached it via the in-modal Return
     * button, send them back to the verify modal; otherwise just close.
     */
    public function handleReturnCancel($recordId = null)
    {
        if ($this->returnCameFromVerify) {
            $this->returnCameFromVerify = false;
            if ($recordId) {
                $this->swapMountedTableAction('verify', (string) $recordId);

                return;
            }
        }

        $this->returnCameFromVerify = false;
        $this->mountedTableAction = null;
        $this->mountedTableActionRecord = null;
        $this->dispatchBrowserEvent('close-modal', [
            'id' => "{$this->id}-table-action",
        ]);
    }

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
            SelectFilter::make('voucher_subtype_id')
                ->label('Voucher Sub Type')
                ->options(fn () => VoucherSubType::pluck('name', 'id'))
                ->query(fn (Builder $query, array $data): Builder => $query->when($data['value'], fn (Builder $query, $value) => $query->whereHas('disbursement_voucher', fn (Builder $q) => $q->where('voucher_subtype_id', $value)))),
            SelectFilter::make('phase')
                ->options([
                    'verification' => 'For Verification',
                    'certification' => 'For Certification',
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['value'], function (Builder $query, string $value) {
                        if ($value === 'verification') {
                            $query->whereIn('current_step_id', [6000, 7000]);
                        } elseif ($value === 'certification') {
                            $query->whereIn('current_step_id', [8000]);
                        }
                    });
                })
                ->label('Phase'),
            Filter::make('report_date')
                ->form([
                    Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('from'),
                            Forms\Components\DatePicker::make('until'),
                        ]),
                ])
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['from'] ?? null) {
                        $indicators['from'] = 'Date from '.Carbon::parse($data['from'])->toFormattedDateString();
                    }
                    if ($data['until'] ?? null) {
                        $indicators['until'] = 'Date until '.Carbon::parse($data['until'])->toFormattedDateString();
                    }

                    return $indicators;
                })
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['from'], fn (Builder $query, $date): Builder => $query->whereDate('report_date', '>=', $date))
                        ->when($data['until'], fn (Builder $query, $date): Builder => $query->whereDate('report_date', '<=', $date));
                }),
        ];
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('tracking_number')->searchable()->toggleable()
                ->extraAttributes(['style' => 'min-width:140px']),
            TextColumn::make('disbursement_voucher.tracking_number')->label('Disbursement voucher')->toggleable()
                ->extraAttributes(['style' => 'min-width:140px']),
            TextColumn::make('particulars')
                ->label('Particulars')
                ->getStateUsing(fn ($record) => Str::limit(strip_tags(collect($record->particulars)->first()['purpose'] ?? ''), 50))
                ->tooltip(fn ($record) => strip_tags(collect($record->particulars)->first()['purpose'] ?? ''))
                ->wrap()
                ->toggleable()
                ->extraAttributes(['style' => 'min-width:200px']),
            TextColumn::make('disbursement_voucher.voucher_subtype.name')->label('Voucher Sub Type')->wrap()->toggleable()
                ->extraAttributes(['style' => 'min-width:150px']),
            TextColumn::make('requisitioner.employee_information.full_name')->searchable()->wrap()->label('Requisitioner')->toggleable()
                ->extraAttributes(['style' => 'min-width:140px']),
            TextColumn::make('disbursement_voucher.payee')->searchable()->wrap()->label('Payee')->toggleable()
                ->extraAttributes(['style' => 'min-width:140px']),
            TextColumn::make('report_date')->date()->label('Date')->toggleable()
                ->extraAttributes(['style' => 'min-width:120px']),
            TextColumn::make('total_amount')->label('Amount')->money('php', true)->toggleable(),
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
                        'description' => $record->current_step->process.' '.auth()->user()->employee_information->full_name,
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
                    if (! $record) {
                        Notification::make()->title('Selected document not found.')->warning()->send();

                        return false;
                    }

                    return $record->current_step->process == 'Forwarded to' && $record->for_cancellation == false && blank($record->pending_return_step_id);
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
                    'description' => $record->current_step->process.' '.$record->current_step->recipient.' by '.auth()->user()->employee_information->full_name,
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
                    if (! $record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();

                        return false;
                    }

                    return $record->certified_by_accountant && ! $record->for_cancellation && blank($record->pending_return_step_id);
                })
                ->requiresConfirmation(),
            Action::make('return')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                if ($record->current_step_id < $record->previous_step_id) {
                    $previous_step_id = $record->previous_step_id;
                } else {
                    $previous_step_id = LiquidationReportStep::where('process', 'Forwarded to')->where('id', '<', $record->current_step->id)->latest('id')->first()->id;
                }
                $record->update([
                    'current_step_id' => $data['return_step_id'],
                    'previous_step_id' => $previous_step_id,
                ]);
                $record->refresh();
                $record->activity_logs()->create([
                    'description' => 'Disbursement Voucher returned to '.$record->current_step->recipient,
                    'remarks' => $data['remarks'] ?? null,
                ]);
                DB::commit();

                $this->notifyReturn($record, $data['remarks'] ?? null);

                Notification::make()->title('Disbursement Voucher returned.')->success()->send();
            })
                ->color('danger')
                ->visible(function ($record) {
                    if (! $record) {
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
            Action::make('release')
                ->label('Release Document')
                ->button()
                ->color('success')
                ->icon('ri-hand-coin-line')
                ->modalHeading('Release Liquidation Report')
                ->modalButton('Confirm Release')
                ->requiresConfirmation()
                ->form([
                    Placeholder::make('release_destination')
                        ->label('Return Destination')
                        ->content(fn ($record) => $record->pending_return_step?->recipient ?? 'Unknown'),
                    TextInput::make('release_log_number')
                        ->label('Log Number')
                        ->required(),
                    Textarea::make('release_note')
                        ->label('Note (Optional)'),
                ])
                ->action(function ($record, $data) {
                    DB::beginTransaction();
                    $previous_step_id = LiquidationReportStep::where('process', 'Forwarded to')->where('id', '<', $record->current_step_id)->latest('id')->first()?->id;
                    $destination = $record->pending_return_step?->recipient;
                    $record->update([
                        'current_step_id' => $record->pending_return_step_id,
                        'previous_step_id' => $previous_step_id,
                        'pending_return_step_id' => null,
                    ]);
                    $record->refresh();
                    $record->activity_logs()->create([
                        'description' => 'LR released to '.$destination.'. Log #: '.$data['release_log_number'],
                        'remarks' => $data['release_note'] ?? null,
                    ]);
                    DB::commit();
                    Notification::make()->title('Document released successfully.')->success()->send();
                })
                ->visible(fn ($record) => $record && filled($record->pending_return_step_id)),
            Action::make('resolve_return')
                ->label('Resolve Return')
                ->button()
                ->color('warning')
                ->icon('ri-arrow-go-back-line')
                ->modalHeading('Resolve Return')
                ->modalButton('Confirm Resolve')
                ->requiresConfirmation()
                ->form([
                    Textarea::make('resolve_note')
                        ->label('Remarks (Optional)'),
                ])
                ->action(function ($record, $data) {
                    DB::beginTransaction();
                    $record->update([
                        'pending_return_step_id' => null,
                    ]);
                    $record->activity_logs()->create([
                        'description' => 'Return resolved by '.auth()->user()->employee_information->full_name,
                        'remarks' => $data['resolve_note'] ?? null,
                    ]);
                    DB::commit();
                    Notification::make()->title('Return resolved successfully.')->success()->send();
                })
                ->visible(fn ($record) => $record && filled($record->pending_return_step_id)),
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

            })
                ->visible(function ($record) {
                    if (! $record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();

                        return false;
                    }

                    return $record->current_step_id == 4000 && $record->for_cancellation && ! $record->cancelled_at;
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
                        'items' => $data['items'] ?? [],
                        'remarks' => $data['remarks'] ?? '',
                    ],
                    'current_step_id' => $record->current_step->next_step->id,
                ]);
                $record->refresh();
                $record->activity_logs()->create([
                    'description' => 'Liquidation Report verified.',
                ]);
                DB::commit();
                Notification::make()->title('Liquidation Report verified.')->success()->send();
            })
                ->mountUsing(function ($form, $record) {
                    $documents = $record?->disbursement_voucher?->voucher_subtype?->related_documents_list?->liquidation_report_documents ?? [];
                    $existingByDoc = $record->getRelatedDocumentItems()->keyBy('document');

                    $form->fill([
                        'lr_number' => $record->lr_number,
                        'journal_date' => $record->journal_date,
                        'items' => collect($documents)->map(function ($doc) use ($existingByDoc) {
                            $existing = $existingByDoc->get($doc);

                            return [
                                'document' => $doc,
                                'status' => $existing['status'] ?? 'required',
                                'remarks' => $existing['remarks'] ?? null,
                            ];
                        })->values()->all(),
                        'remarks' => $record->getRelatedDocumentsGeneralRemarks(),
                    ]);
                })
                ->visible(function ($record) {
                    if (! $record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();

                        return false;
                    }

                    return $record->current_step_id == 7000 && blank($record->journal_date) && blank($record->lr_number) && ! $record->for_cancellation && blank($record->pending_return_step_id);
                })
                ->form(function () {
                    return [
                        Placeholder::make('lr_details')
                            ->label('')
                            ->content(fn ($record) => view('components.liquidation_reports.lr-details-card', [
                                'record' => $record,
                            ])),
                        TextInput::make('lr_number')
                            ->label('LR Number')
                            ->required(),
                        Flatpickr::make('journal_date')
                            ->disableTime()
                            ->required(),
                        RelatedDocumentsChecklist::make('items')
                            ->label('Documentary Requirements')
                            ->documents(fn ($record) => $record?->disbursement_voucher?->voucher_subtype?->related_documents_list?->liquidation_report_documents ?? [])
                            ->rule(function () {
                                return function (string $attribute, $value, \Closure $fail) {
                                    if (! is_array($value)) {
                                        return;
                                    }
                                    foreach ($value as $item) {
                                        if (($item['status'] ?? null) === 'not_required') {
                                            $fail('Cannot verify while there are documents marked "For Compliance". Please return the document instead.');

                                            return;
                                        }
                                    }
                                };
                            }),
                        RichEditor::make('remarks')
                            ->label('General Remarks (Optional)'),
                        Placeholder::make('return_trigger')
                            ->label('')
                            ->content(fn ($record) => view('forms.components.lr-return-trigger', [
                                'recordId' => $record?->getKey(),
                            ])),
                    ];
                })
                ->modalHeading('Verify Liquidation Report')
                ->modalButton('Verify')
                ->modalWidth('7xl'),
            Action::make('certify')->button()->action(function ($record) {
                DB::beginTransaction();
                $record->update([
                    'certified_by_accountant' => true,
                ]);
                $record->recordAccountantApproval(auth()->id());
                $record->activity_logs()->create([
                    'description' => 'Liquidation Report certified.',
                ]);
                DB::commit();

                // ========== SMS NOTIFICATION ==========
                $record->load(['disbursement_voucher.user.employee_information']);
                $trackingNumber = $record->tracking_number;
                $message = "Your LR with ref. no. {$trackingNumber} has been approved.";

                $requestedBy = $record->disbursement_voucher->user;
                if ($requestedBy && $requestedBy->employee_information && ! empty($requestedBy->employee_information->contact_number)) {
                    SendSmsJob::dispatch(
                        $requestedBy->employee_information->contact_number,
                        $message,
                        'liquidation_report_approved',
                        $requestedBy->id,
                        auth()->id()
                    );
                }
                // ========== SMS NOTIFICATION END ==========

                // ========== REALTIME NOTIFICATION ==========
                try {
                    if ($requestedBy) {
                        NotificationController::sendGeneralNotification(
                            'liquidation_report_approved',
                            'Liquidation Report Approved',
                            $message,
                            $requestedBy,
                            route('requisitioner.liquidation-reports.show', ['liquidation_report' => $record])
                        );
                    }
                } catch (\Exception $e) {
                    \Log::error('Realtime notification failed: '.$e->getMessage());
                }
                // ========== REALTIME NOTIFICATION END ==========

                Notification::make()->title('Liquidation Report certified.')->success()->send();
            })
                ->visible(fn ($record) => $record->current_step_id == 8000 && ! $record->for_cancellation && ! $record->certified_by_accountant && blank($record->pending_return_step_id) && auth()->user()->employee_information->position_id == auth()->user()->employee_information->office->head_position_id)
                ->requiresConfirmation(),
            // Return is intentionally the last button, just before the grouped (eye) actions.
            Action::make('returnFromAccounting')
                ->label('Return')
                ->button()
                ->color('danger')
                ->icon('ri-arrow-go-back-line')
                ->modalHeading('Return Liquidation Report')
                ->modalSubheading('Select which previous office should receive this LR and explain what needs to be fixed. The requisitioner will be notified by SMS.')
                ->modalWidth('4xl')
                ->modalCancelAction(function ($action) {
                    $recordId = $action->getRecord()?->getKey();

                    return $action->makeModalAction('cancel')
                        ->label(__('filament-support::actions/modal.actions.cancel.label'))
                        ->action('handleReturnCancel', $recordId ? [(string) $recordId] : [])
                        ->color('secondary');
                })
                ->form(function () {
                    return [
                        Select::make('return_step_id')
                            ->label('Return to')
                            // Accounting can only bounce an LR back to a pre-accounting office
                            // (Requisitioner / Signatory). Restricting to steps before 5000 also
                            // avoids offering "Accounting Office" itself at step 7000, whose
                            // recipient is "-" and so isn't caught by a recipient filter.
                            ->options(fn ($record) => LiquidationReportStep::where('process', 'Forwarded to')->where('id', '<', 5000)->pluck('recipient', 'id'))
                            ->required(),
                        RichEditor::make('remarks')
                            ->label('Return Reason')
                            ->required()
                            ->fileAttachmentsDisk('remarks'),
                    ];
                })
                ->action(function ($record, $data) {
                    DB::beginTransaction();
                    $record->update([
                        'pending_return_step_id' => $data['return_step_id'],
                    ]);
                    $record->refresh();
                    $record->activity_logs()->create([
                        'description' => 'LR marked for return to '.$record->pending_return_step->recipient.'. Awaiting physical release.',
                        'remarks' => $data['remarks'] ?? null,
                    ]);
                    DB::commit();

                    $this->notifyReturn($record, $data['remarks'] ?? null);

                    Notification::make()->title('LR marked for return. Use "Release Document" when the hardcopy is picked up.')->success()->send();
                })
                ->visible(function ($record) {
                    if (! $record) {
                        return false;
                    }

                    return in_array($record->current_step_id, [5000, 6000, 7000]) && $record->for_cancellation == false && blank($record->pending_return_step_id);
                }),
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

    protected function getTableRecordClassesUsing(): ?\Closure
    {
        return fn (LiquidationReport $record): string => filled($record->pending_return_step_id) ? '!bg-rose-100' : '';
    }

    /**
     * Send the SMS + realtime notification to the requisitioner when an LR is returned.
     */
    protected function notifyReturn(LiquidationReport $record, ?string $remarks): void
    {
        $record->load(['disbursement_voucher.user.employee_information']);
        $trackingNumber = $record->tracking_number;
        $officerName = auth()->user()->employee_information->full_name ?? 'Officer';
        $remarks = html_entity_decode(strip_tags($remarks ?? ''), ENT_QUOTES, 'UTF-8');
        if (blank($remarks)) {
            $remarks = 'No remarks provided';
        }

        $message = "Your LR with ref. no. {$trackingNumber} has been returned by {$officerName} with the following remarks: \"{$remarks}\". Please retrieve your documents immediately.";

        $requestedBy = $record->disbursement_voucher->user;
        if ($requestedBy && $requestedBy->employee_information && ! empty($requestedBy->employee_information->contact_number)) {
            SendSmsJob::dispatch(
                $requestedBy->employee_information->contact_number,
                $message,
                'liquidation_report_returned',
                $requestedBy->id,
                auth()->id()
            );
        }

        try {
            if ($requestedBy) {
                NotificationController::sendGeneralNotification(
                    'liquidation_report_returned',
                    'Liquidation Report Returned',
                    $message,
                    $requestedBy,
                    route('requisitioner.liquidation-reports.show', ['liquidation_report' => $record])
                );
            }
        } catch (\Exception $e) {
            \Log::error('Realtime notification failed: '.$e->getMessage());
        }
    }

    public function mount()
    {
        if (! in_array(auth()->user()->employee_information?->office->office_group_id, [2])) {
            abort(403, 'You are not allowed to access this page.');
        }
    }

    public function render()
    {
        return view('livewire.offices.office-liquidation-reports-index');
    }
}
