<?php

namespace App\Http\Livewire\Offices\Traits;

use App\Forms\Components\Flatpickr;
use App\Forms\Components\RelatedDocumentsChecklist;
use App\Http\Controllers\NotificationController;
use App\Jobs\SendSmsJob;
use App\Models\CategoryItemBudget;
use App\Models\DisbursementVoucher;
use App\Models\DisbursementVoucherStep;
use App\Models\DvAdjustment;
use App\Models\FundCluster;
use App\Models\Mop;
use App\Models\TravelOrderType;
use App\Services\DisbursementVouchers\DisbursementVoucherWorkflowService;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait OfficeDashboardActions
{
    public bool $icuReturnCameFromVerify = false;

    public function isOic()
    {
        return false;
    }

    /**
     * The user an OIC action is being performed on behalf of (the office "as" filter),
     * or null when not acting as OIC / no filter selected. Used to freeze the slot
     * owner whose name belongs on the signature block.
     */
    protected function slotOwnerId(): ?int
    {
        return $this->tableFilters['as']['value'] ?? null;
    }

    /**
     * Smart Cancel for the ICU Return modal.
     * If the user got here via the in-modal Return Document swap, send them
     * back to the verify modal so they can keep marking documents. If they
     * got here via the row's red Return Document button, close the modal.
     */
    public function handleIcuReturnCancel($recordId = null)
    {
        if ($this->icuReturnCameFromVerify) {
            $this->icuReturnCameFromVerify = false;
            $record = $recordId ? DisbursementVoucher::find($recordId) : null;
            if ($record) {
                $this->swapMountedTableAction('edit', (string) $record->getKey());

                return;
            }
        }

        $this->icuReturnCameFromVerify = false;
        $this->mountedTableAction = null;
        $this->mountedTableActionRecord = null;
        $this->dispatchBrowserEvent('close-modal', [
            'id' => "{$this->id}-table-action",
        ]);
    }

    /**
     * Swap the currently mounted table action to a different one, forcing
     * Filament to rebuild the cached form so the new action's schema is used
     * instead of the previous action's stale schema. Plain mountTableAction()
     * does not invalidate the cached mountedTableActionForm, which causes the
     * old form fields to render with the new action's heading.
     */
    protected function swapMountedTableAction(string $actionName, string $recordId)
    {
        // Drop the cached form so the next render rebuilds with the new schema.
        if (property_exists($this, 'cachedForms') && is_array($this->cachedForms)) {
            unset($this->cachedForms['mountedTableActionForm']);
        }
        $this->mountedTableActionData = [];

        $this->mountTableAction($actionName, $recordId);
    }

    /**
     * Triggered by the in-modal "Return Document" button on the ICU verify pop-up.
     * Swaps the same Filament modal element from the verify form to the Return
     * form by re-mounting the dedicated 'returnFromIcu' action.
     *
     * Intentionally does NOT persist anything to the DV. Clicking Return is
     * the verifier saying "this DV is not acceptable, bounce it back" - we
     * leave related_documents untouched so the DV stays in the un-verified
     * state. The return reason captured in the next modal goes into the
     * activity log, which is the audit trail. When the DV comes back to ICU
     * later, the verify pop-up opens fresh.
     */
    public function openIcuReturnFromVerify($recordId)
    {
        $record = DisbursementVoucher::find($recordId);
        if (! $record) {
            Notification::make()->title('Disbursement Voucher not found.')->danger()->send();

            return;
        }

        // Mark that we entered Return from the verify pop-up so Cancel can swap back.
        $this->icuReturnCameFromVerify = true;

        // Swap the same modal element from the verify action to the return action.
        $this->swapMountedTableAction('returnFromIcu', (string) $recordId);
    }

    private function officeTableColumns()
    {
        return [
            TextColumn::make('tracking_number')->searchable()->toggleable()
                ->extraAttributes(['style' => 'min-width:140px']),
            TextColumn::make('particulars')
                ->label('Particulars')
                ->getStateUsing(fn ($record) => Str::limit(strip_tags($record->disbursement_voucher_particulars->first()?->purpose ?? ''), 50))
                ->tooltip(fn ($record) => strip_tags($record->disbursement_voucher_particulars->first()?->purpose ?? ''))
                ->wrap()
                ->toggleable()
                ->extraAttributes(['style' => 'min-width:200px']),
            TextColumn::make('voucher_subtype.voucher_type.name')->label('Voucher Type')->wrap()->toggleable(isToggledHiddenByDefault: true)
                ->extraAttributes(['style' => 'min-width:120px']),
            TextColumn::make('voucher_subtype.name')->label('Voucher Sub Type')->wrap()->toggleable()
                ->extraAttributes(['style' => 'min-width:150px']),
            TextColumn::make('user.employee_information.full_name')->searchable()->wrap()->label('Requisitioner')->toggleable()
                ->extraAttributes(['style' => 'min-width:140px']),
            TextColumn::make('payee')->searchable()->wrap()->label('Payee')->toggleable()
                ->extraAttributes(['style' => 'min-width:140px']),
            TextColumn::make('submitted_at')->dateTime('F d, Y')->toggleable()
                ->extraAttributes(['style' => 'min-width:120px']),
            TextColumn::make('gross_amount')->label('Gross Amount')->money('php', true)->toggleable(),
            TextColumn::make('disbursement_voucher_particulars_sum_amount')->sum('disbursement_voucher_particulars', 'amount')->label('Net Amount')->money('php', true)->toggleable(),
        ];
    }

    private function canBeForwarded($record)
    {
        if (! $record) {
            Notification::make()->title('Selected document not found in office.')->warning()->send();

            return;
        }

        return app(DisbursementVoucherWorkflowService::class)->canBeForwarded($record);
    }

    private function viewActions()
    {
        return [
            ActionGroup::make([
                ViewAction::make('progress')
                    ->label('Progress')
                    ->icon('ri-loader-4-fill')
                    ->modalHeading('Disbursement Voucher Progress')
                    ->modalContent(fn ($record) => view('components.timeline_views.progress_logs', [
                        'record' => $record,
                        'steps' => DisbursementVoucherStep::whereEnabled(true)->where('id', '>', 2000)->get(),
                    ])),
                ViewAction::make('logs')
                    ->label('Activity Timeline')
                    ->icon('ri-list-check-2')
                    ->modalHeading('Disbursement Voucher Activity Timeline')
                    ->modalContent(fn ($record) => view('components.timeline_views.activity_logs', [
                        'record' => $record,
                    ])),
                ViewAction::make('related_documents')
                    ->label('Related Documents')
                    ->icon('ri-file-copy-2-line')
                    ->modalHeading('Disbursement Voucher Related Documents')
                    ->modalContent(fn ($record) => view('components.disbursement_vouchers.disbursement_voucher_documents', [
                        'disbursement_voucher' => $record,
                    ])),
                ViewAction::make('ctc')
                    ->label('Certificate of Travel Completion')
                    ->icon('ri-file-text-line')
                    ->url(fn ($record) => route('ctc.show', ['ctc' => $record->travel_completed_certificate]), true)
                    ->visible(fn ($record) => $record->travel_completed_certificate()->exists()),
                ViewAction::make('actual_itinerary')
                    ->label('Actual Itinerary')
                    ->icon('ri-file-copy-line')
                    ->url(fn ($record) => route('signatory.itinerary.print', ['itinerary' => $record->travel_order->itineraries()->where('user_id', $record->user_id)->whereIsActual(true)->first()]), true)
                    ->visible(fn ($record) => $record->travel_order?->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS && $record->travel_order?->itineraries()->where('user_id', $record->user_id)->whereIsActual(true)->exists()),
                ViewAction::make('view')
                    ->label('Preview')
                    ->openUrlInNewTab()
                    ->url(fn ($record) => route('disbursement-vouchers.show', ['disbursement_voucher' => $record]), true),
                ViewAction::make('supporting_documents')
                    ->label('Supporting Documents')
                    ->icon('ri-attachment-line')
                    ->modalHeading('Supporting Documents')
                    ->modalContent(fn ($record) => view('components.disbursement_vouchers.supporting-documents', [
                        'documents' => $record->scanned_documents,
                    ]))
                    ->visible(fn ($record) => $record->scanned_documents()->exists()),
                ViewAction::make('adjustment_history')
                    ->label('Adjustment History')
                    ->icon('ri-history-line')
                    ->modalHeading('DV Adjustment History')
                    ->modalWidth('4xl')
                    ->modalContent(fn ($record) => view('components.disbursement_vouchers.dv-adjustment-history', [
                        'adjustments' => $record->dv_adjustments()->with('adjusted_by_user.employee_information')->latest()->get(),
                    ]))
                    ->visible(fn ($record) => $record->dv_adjustments()->exists()),
            ])->icon('ri-eye-line'),
        ];
    }

    private function icuVerifyAction()
    {
        return [
            EditAction::make()
                ->button()
                ->icon('ri-file-copy-2-line')
                ->label('Verify Related Documents')
                ->modalHeading('Verify Related Documents')
                ->modalButton('Verify Related Documents')
                ->modalWidth('7xl')
                ->mountUsing(function ($form, $record) {
                    $documents = $record?->voucher_subtype?->related_documents_list?->documents ?? [];
                    $existingItems = $record->related_documents['items'] ?? [];
                    $existingByDoc = collect($existingItems)->keyBy('document');

                    $form->fill([
                        'log_number' => $record->log_number,
                        'items' => collect($documents)->map(function ($doc) use ($existingByDoc) {
                            $existing = $existingByDoc->get($doc);
                            return [
                                'document' => $doc,
                                'status' => $existing['status'] ?? 'required',
                                'remarks' => $existing['remarks'] ?? null,
                            ];
                        })->values()->all(),
                        'remarks' => $record->related_documents['remarks'] ?? null,
                    ]);
                })
                ->action(function ($record, $data) {
                    $record->refresh();
                    app(DisbursementVoucherWorkflowService::class)->verifyRelatedDocuments($record, $data, [
                        'is_oic' => $this->isOic(),
                        'actor' => auth()->user(),
                    ]);
                    Notification::make()->title('Related documents have been verified.')->success()->send();
                })
                ->form([
                    Placeholder::make('dv_details')
                        ->label('')
                        ->content(fn ($record) => view('components.disbursement_vouchers.dv-details-card', [
                            'record' => $record,
                        ])),
                    TextInput::make('log_number'),
                    RelatedDocumentsChecklist::make('items')
                        ->label('Documentary Requirements')
                        ->documents(fn ($record) => $record?->voucher_subtype?->related_documents_list?->documents ?? [])
                        ->required(fn ($record) => filled($record?->voucher_subtype?->related_documents_list?->documents ?? []))
                        ->rule(function () {
                            return function (string $attribute, $value, \Closure $fail) {
                                if (! is_array($value)) {
                                    $fail('Invalid checklist data.');

                                    return;
                                }
                                foreach ($value as $i => $item) {
                                    $status = $item['status'] ?? null;
                                    if (! in_array($status, ['required', 'not_required', 'not_applicable'])) {
                                        $fail('Each document must have a status set.');

                                        return;
                                    }
                                    if ($status === 'not_required') {
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
                        ->content(fn ($record) => view('forms.components.icu-return-trigger', [
                            'recordId' => $record?->getKey(),
                        ])),
                ])->visible(function ($record) {
                    if (! $record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();

                        return false;
                    }

                    return $record->current_step_id == 6000 && $record->for_cancellation == false && $record->voucher_subtype?->related_documents_list && ! $record->hasCompletedRelatedDocumentsVerification() && blank($record->pending_return_step_id);
                }),
        ];
    }

    private function icuReturnAction()
    {
        return [
            Action::make('returnFromIcu')
                ->label('Return Document')
                ->button()
                ->color('danger')
                ->icon('ri-arrow-go-back-line')
                ->modalHeading('Return Disbursement Voucher')
                ->modalSubheading('Select which previous office should receive this DV and explain what needs to be fixed. The requisitioner will be notified by SMS.')
                ->modalWidth('4xl')
                ->modalCancelAction(function ($action) {
                    $recordId = $action->getRecord()?->getKey();

                    return $action->makeModalAction('cancel')
                        ->label(__('filament-support::actions/modal.actions.cancel.label'))
                        ->action('handleIcuReturnCancel', $recordId ? [(string) $recordId] : [])
                        ->color('secondary');
                })
                ->form(function () {
                    return [
                        Select::make('return_step_id')
                            ->label('Return to')
                            ->options(fn ($record) => DisbursementVoucherStep::where('process', 'Forwarded to')->where('recipient', '!=', $record->current_step->recipient)->where('id', '<', $record->current_step_id)->pluck('recipient', 'id'))
                            ->required(),
                        RichEditor::make('remarks')
                            ->label('Return Reason')
                            ->required()
                            ->fileAttachmentsDisk('remarks'),
                    ];
                })
                ->action(function ($record, $data) {
                    app(DisbursementVoucherWorkflowService::class)->returnToStep($record, $data['return_step_id'], $data['remarks'] ?? null, [
                        'is_oic' => $this->isOic(),
                        'actor' => auth()->user(),
                    ]);

                    // ========== SMS NOTIFICATION ==========
                    $record->load(['user.employee_information']);
                    $trackingNumber = $record->tracking_number;
                    $officerName = auth()->user()->employee_information->full_name ?? 'Officer';
                    $remarks = strip_tags($data['remarks'] ?? '');
                    $remarks = html_entity_decode($remarks, ENT_QUOTES, 'UTF-8');
                    if (blank($remarks)) {
                        $remarks = 'No remarks provided';
                    }
                    $message = "Your DV with ref. no. {$trackingNumber} has been returned by {$officerName} with the following remarks: \"{$remarks}\". Please retrieve your documents immediately.";
                    $requestedBy = $record->user;
                    if ($requestedBy && $requestedBy->employee_information && ! empty($requestedBy->employee_information->contact_number)) {
                        SendSmsJob::dispatch(
                            $requestedBy->employee_information->contact_number,
                            $message,
                            'disbursement_voucher_returned',
                            $requestedBy->id,
                            auth()->id()
                        );
                    }
                    // ========== SMS NOTIFICATION END ==========

                    $this->emit('refresh');Notification::make()->title('DV marked for return. Use "Release Document" when the hardcopy is picked up.')->success()->send();
                })
                ->visible(function ($record) {
                    if (! $record) {
                        return false;
                    }

                    return $record->current_step_id == 6000 && $record->for_cancellation == false && blank($record->pending_return_step_id);
                }),
        ];
    }

    private function releaseAction()
    {
        return [
            Action::make('release')
                ->label('Release Document')
                ->button()
                ->color('success')
                ->icon('ri-hand-coin-line')
                ->modalHeading('Release Disbursement Voucher')
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
                    app(DisbursementVoucherWorkflowService::class)->releaseReturn($record, auth()->user(), $data['release_log_number'], $data['release_note'] ?? null, [
                        'is_oic' => $this->isOic(),
                    ]);
                    $this->emit('refresh');Notification::make()->title('Document released successfully.')->success()->send();
                })
                ->visible(fn ($record) => $record && filled($record->pending_return_step_id)),
        ];
    }

    private function resolveReturnAction()
    {
        return [
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
                    app(DisbursementVoucherWorkflowService::class)->resolveReturn($record, auth()->user(), $data['resolve_note'] ?? null, [
                        'is_oic' => $this->isOic(),
                    ]);
                    $this->emit('refresh');
                    Notification::make()->title('Return resolved successfully.')->success()->send();
                })
                ->visible(fn ($record) => $record && filled($record->pending_return_step_id)),
        ];
    }

    private function cashierActions()
    {
        return [
            Action::make('cheque_ada')->label('Cheque/ADA')->button()->action(function ($record, $data) {
                app(DisbursementVoucherWorkflowService::class)->makeChequeAda($record, $data['mop_id'], $data['cheque_number'], [
                    'is_oic' => $this->isOic(),
                    'actor' => auth()->user(),
                ]);
                $record->refresh();

                $receiver = $record->user;
                NotificationController::cashAdvanceCreation(Auth::user(), $receiver, $record);

                // ========== SMS NOTIFICATION ==========
                // Send SMS notification
                $record->load(['user.employee_information']);
                $trackingNumber = $record->tracking_number;
                $chequeNumber = $data['cheque_number'];
                $message = "Your DV with ref. no. {$trackingNumber} is ready for disbursement with check/ADA number {$chequeNumber}.";

                $requestedBy = $record->user;
                if ($requestedBy && $requestedBy->employee_information && ! empty($requestedBy->employee_information->contact_number)) {
                    SendSmsJob::dispatch(
                        $requestedBy->employee_information->contact_number,
                        $message,
                        'disbursement_voucher_ready',
                        $requestedBy->id,
                        auth()->id()
                    );
                }
                // ========== SMS NOTIFICATION END ==========

                Notification::make()->title('Cheque/ADA made for requisitioner.')->success()->send();
            })
                ->visible(function ($record) {
                    if (! $record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();

                        return false;
                    }

                    return $record->current_step_id == 17000 && blank($record->cheque_number) && $record->for_cancellation == false && blank($record->pending_return_step_id);
                })
                ->form(function () {
                    return [
                        Grid::make(2)
                            ->schema([
                                Placeholder::make('dv_number')->label('DV Number')->content(fn ($record) => $record->dv_number ?? 'N/A'),
                                Placeholder::make('ors_burs')->label('ORS/BURS')->content(fn ($record) => $record->ors_burs ?? 'N/A'),
                            ]),
                        Placeholder::make('fund_cluster')->label('Fund Cluster')->content(fn ($record) => $record->fund_cluster->name ?? 'N/A'),
                        TextInput::make('cheque_number')
                            ->label('Cheque number/ADA')
                            ->required(),
                        Select::make('mop_id')
                            ->label('Mode of Payment')
                            ->options(Mop::pluck('name', 'id'))
                            ->required(),
                    ];
                })
                ->requiresConfirmation(),
            Action::make('cancel')
                ->requiresConfirmation()
                ->action(function ($record, $data) {
                    $record->update([
                        'cancellation_remarks' => $data['cancellation_remarks'],
                        'for_cancellation' => true,
                        'cancelled_at' => now(),
                    ]);
                    $description = 'Cheque/ADA cancelled.';
                    $record->activity_logs()->create([
                        'description' => $description,
                    ]);
                })
                ->form([
                    Textarea::make('cancellation_remarks'),
                ])
                ->button()
                ->color('danger')
                ->visible(fn ($record) => $record->cheque_number && $record->current_step_id == 18000 && ! $record->for_cancellation && blank($record->pending_return_step_id)),
        ];
    }

    private function accountingActions()
    {
        return [
            Action::make('verify')->button()->action(function ($record, $data) {
                app(DisbursementVoucherWorkflowService::class)->recordAccounting($record, $data['dv_number'], $data['journal_date'], [
                    'is_oic' => $this->isOic(),
                    'actor' => auth()->user(),
                ]);
                Notification::make()->title('Disbursement Voucher verified.')->success()->send();
            })
                ->visible(function ($record) {
                    if (! $record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();

                        return false;
                    }

                    return $record->current_step_id == 12000 && blank($record->journal_date) && blank($record->dv_number) && $record->for_cancellation == false && blank($record->pending_return_step_id);
                })
                ->form(function () {
                    return [
                        TextInput::make('dv_number')
                            ->label('DV Number')
                            ->required(),
                        Flatpickr::make('journal_date')
                            ->disableTime()
                            ->required(),
                    ];
                })
                ->requiresConfirmation(),
        ];
    }

    private function budgetOfficeActions()
    {
        return [
            Action::make('ors_burs')->label('ORS/BURS')->button()->action(function ($record, $data) {
                try {
                    app(DisbursementVoucherWorkflowService::class)->assignOrsBurs($record, $data, [
                        'is_oic' => $this->isOic(),
                        'actor' => auth()->user(),
                    ]);
                    Notification::make()->title('ORS/BURS, Fund Cluster, and UACS allocations updated.')->success()->send();
                } catch (ValidationException $exception) {
                    foreach ($exception->errors() as $field => $messages) {
                        foreach ($messages as $message) {
                            Notification::make()->title($message)->danger()->send();
                        }
                    }
                    Notification::make()->title('Please check the highlighted fields.')->danger()->send();
                    throw $exception;
                } catch (\Throwable $exception) {
                    report($exception);
                    Notification::make()->title('Workflow action failed.')->body($exception->getMessage())->danger()->send();
                }

            })
                ->visible(function ($record) {
                    if (! $record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();

                        return false;
                    }

                    return $record->current_step_id == 9000 && (blank($record->ors_burs) || blank($record->fund_cluster_id) || ! $record->hasValidUacsAllocations()) && $record->for_cancellation == false && blank($record->pending_return_step_id);
                })
                ->form(function ($record) {
                    $uacsAllocationDefaults = $record->uacs_allocations->isNotEmpty()
                        ? $record->uacs_allocations->map(fn ($allocation) => [
                            'category_item_budget_id' => $allocation->category_item_budget_id,
                            'amount' => $allocation->amount,
                        ])->values()->all()
                        : [
                            [
                                'category_item_budget_id' => null,
                                'amount' => $record->totalSumDisbursementVoucherParticular(),
                            ],
                        ];

                    return [
                        Placeholder::make('voucher_total')
                            ->label('DV Amount')
                            ->content(fn ($record) => 'PHP '.number_format($record->totalSumDisbursementVoucherParticular(), 2)),
                        Select::make('fund_cluster_id')
                            ->label('Fund Cluster')
                            ->options(FundCluster::whereIn('id', [1, 2, 3, 8])->pluck('name', 'id'))
                            ->default($record->fund_cluster_id)
                            ->required(),
                        TextInput::make('ors_burs')
                            ->label('ORS/BURS')
                            ->default($record->ors_burs)
                            ->required(),
                        TextInput::make('responsibility_center')
                            ->default($record->responsibility_center)
                            ->required(),
                        TableRepeater::make('uacs_allocations')
                            ->label('UACS Allocations')
                            ->hideLabels()
                            ->columnWidths([
                                0 => '70%',
                                1 => '30%',
                                'category_item_budget_id' => '70%',
                                'amount' => '30%',
                            ])
                            ->schema([
                                Select::make('category_item_budget_id')
                                    ->label('UACS Code')
                                    ->options(fn () => CategoryItemBudget::selectRaw("id, concat(uacs_code, ' - ', name) as code")->pluck('code', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->extraAttributes(['style' => 'min-width: 24rem; width: 100%;'])
                                    ->extraInputAttributes(['style' => 'width: 100%;'])
                                    ->required(),
                                TextInput::make('amount')
                                    ->extraAttributes(['style' => 'width: 100%;'])
                                    ->numeric()
                                    ->minValue(0.01)
                                    ->required(),
                            ])
                            ->minItems(1)
                            ->required(),
                    ];
                }),

        ];
    }

    private function amountToCents($amount): int
    {
        return (int) round((float) $amount * 100);
    }

    private function commonActions()
    {
        return [
            Action::make('Receive')->button()->action(function (DisbursementVoucher $record) {
                app(DisbursementVoucherWorkflowService::class)->receive($record, auth()->user(), [
                    'is_oic' => $this->isOic(),
                ]);
                $this->emit('refresh');Notification::make()->title('Document Received')->success()->send();
            })
                ->visible(function ($record) {
                    if (! $record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();

                        return false;
                    }

                    return $record->current_step->process == 'Forwarded to' && $record->for_cancellation == false && blank($record->pending_return_step_id);
                })
                ->requiresConfirmation(),
            Action::make('Forward')->button()->action(function ($record, $data) {
                if ($this->canBeForwarded($record)) {
                    app(DisbursementVoucherWorkflowService::class)->forward($record, auth()->user(), $data['remarks'] ?? null, [
                        'is_oic' => $this->isOic(),
                        'actor' => auth()->user(),
                        'slot_owner_id' => $this->slotOwnerId(),
                    ]);
                    $record->refresh();

                    // ========== SMS NOTIFICATION (DISABLED) ==========
                    // Per Memo No. 75, s. 2025 (Annex A): NO SMS on DV movement/forward —
                    // it floods the requisitioner on every hop. They track status via
                    // their dashboard. DV SMS fires only on submit, return/disapproval,
                    // and check/ADA issuance. Re-enable by uncommenting if policy changes.
                    // $record->load(['user.employee_information', 'current_step']);
                    // $trackingNumber = $record->tracking_number;
                    // $officerName = auth()->user()->employee_information->full_name ?? 'Officer';
                    // $nextRecipient = $record->current_step->recipient ?? 'the next office';
                    // $approverPrefix = $this->isOic() ? 'OIC ' : '';
                    // $message = "Your DV with ref. no. {$trackingNumber} has been approved by {$approverPrefix}{$officerName} and forwarded to {$nextRecipient}.";
                    // $requestedBy = $record->user;
                    // if ($requestedBy && $requestedBy->employee_information && !empty($requestedBy->employee_information->contact_number)) {
                    //     SendSmsJob::dispatch(
                    //         $requestedBy->employee_information->contact_number,
                    //         $message,
                    //         'disbursement_voucher_forwarded',
                    //         $requestedBy->id,
                    //         auth()->id()
                    //     );
                    // }
                    // ========== SMS NOTIFICATION END ==========

                    $this->emit('refresh');
                    Notification::make()->title('Document Forwarded')->success()->send();
                } else {
                    Notification::make()->title('Document cannot be forwarded.')->body('Document may have been updated. Please refresh this page.')->success()->send();
                }
            })
                ->form(function () {
                    return [
                        RichEditor::make('remarks')
                            ->label('Remarks (Optional)')
                            ->fileAttachmentsDisk('remarks'),
                    ];
                })
                ->modalWidth('4xl')
                ->visible(fn ($record) => $this->canBeForwarded($record) && $record->for_cancellation == false)
                ->requiresConfirmation(),
        ];
    }

    private function adjustmentActions()
    {
        $adjustableSteps = [6000, 8000, 9000, 11000, 12000, 13000, 20000];

        return [
            Action::make('amount_adjustment')
                ->label('Amount Adjustment')
                ->button()
                ->color('warning')
                ->icon('ri-edit-line')
                ->modalHeading('Amount Adjustment')
                ->modalButton('Save Adjustment')
                ->modalWidth('5xl')
                ->mountUsing(function ($form, $record) {
                    $form->fill([
                        'payee' => $record->payee,
                        'particulars' => $record->disbursement_voucher_particulars->map(fn ($p) => [
                            'particular_id' => $p->id,
                            'purpose' => $p->purpose,
                            'amount' => $p->amount,
                        ])->toArray(),
                    ]);
                })
                ->action(function ($record, $data) {
                    $record->refresh();
                    DB::beginTransaction();

                    $batchId = Str::uuid()->toString();
                    $changes = [];

                    // Check payee change
                    if ($record->payee !== $data['payee']) {
                        DvAdjustment::create([
                            'disbursement_voucher_id' => $record->id,
                            'field' => 'Payee',
                            'old_value' => $record->payee,
                            'new_value' => $data['payee'],
                            'adjusted_by' => auth()->id(),
                            'batch_id' => $batchId,
                        ]);
                        $record->update(['payee' => $data['payee']]);
                        $changes[] = 'Payee';
                    }

                    // Process particulars
                    $existingParticulars = $record->disbursement_voucher_particulars->keyBy('id');
                    $submittedIds = collect($data['particulars'])->pluck('particular_id')->filter()->all();

                    // Deleted particulars
                    foreach ($existingParticulars as $id => $existing) {
                        if (! in_array($id, $submittedIds)) {
                            DvAdjustment::create([
                                'disbursement_voucher_id' => $record->id,
                                'field' => 'Particular removed',
                                'old_value' => $existing->purpose.' — ₱'.number_format($existing->amount, 2),
                                'new_value' => null,
                                'adjusted_by' => auth()->id(),
                                'batch_id' => $batchId,
                            ]);
                            $existing->delete();
                            $changes[] = 'Particular removed';
                        }
                    }

                    foreach ($data['particulars'] as $item) {
                        $particularId = $item['particular_id'] ?? null;

                        if ($particularId && $existingParticulars->has($particularId)) {
                            // Existing particular - check for changes
                            $existing = $existingParticulars[$particularId];

                            if ($existing->purpose !== $item['purpose']) {
                                DvAdjustment::create([
                                    'disbursement_voucher_id' => $record->id,
                                    'field' => 'Particular purpose',
                                    'old_value' => $existing->purpose,
                                    'new_value' => $item['purpose'],
                                    'adjusted_by' => auth()->id(),
                                    'batch_id' => $batchId,
                                ]);
                                $changes[] = 'Purpose';
                            }

                            if ((float) $existing->amount !== (float) $item['amount']) {
                                DvAdjustment::create([
                                    'disbursement_voucher_id' => $record->id,
                                    'field' => 'Particular amount',
                                    'old_value' => '₱'.number_format($existing->amount, 2).' ('.$existing->purpose.')',
                                    'new_value' => '₱'.number_format($item['amount'], 2),
                                    'adjusted_by' => auth()->id(),
                                    'batch_id' => $batchId,
                                ]);
                                $changes[] = 'Amount';
                            }

                            $existing->update([
                                'purpose' => $item['purpose'],
                                'amount' => $item['amount'],
                            ]);
                        } else {
                            // New particular
                            $record->disbursement_voucher_particulars()->create([
                                'purpose' => $item['purpose'],
                                'amount' => $item['amount'],
                                'mfo_pap' => '',
                            ]);
                            DvAdjustment::create([
                                'disbursement_voucher_id' => $record->id,
                                'field' => 'Particular added',
                                'old_value' => null,
                                'new_value' => $item['purpose'].' — ₱'.number_format($item['amount'], 2),
                                'adjusted_by' => auth()->id(),
                                'batch_id' => $batchId,
                            ]);
                            $changes[] = 'Particular added';
                        }
                    }

                    if (! empty($changes)) {
                        $description = 'DV adjusted ('.implode(', ', array_unique($changes)).') by ';
                        if ($this->isOic()) {
                            $description .= 'OIC: '.auth()->user()->employee_information->full_name.'.';
                        } else {
                            $description .= auth()->user()->employee_information->full_name;
                        }
                        $record->activity_logs()->create([
                            'description' => $description,
                        ]);
                    }

                    DB::commit();

                    if (! empty($changes)) {
                        Notification::make()->title('DV adjusted successfully.')->success()->send();
                    } else {
                        Notification::make()->title('No changes detected.')->warning()->send();
                    }
                })
                ->form([
                    TextInput::make('payee')
                        ->label('Payee')
                        ->required(),
                    Repeater::make('particulars')
                        ->label('Disbursement Voucher Particulars')
                        ->schema([
                            TextInput::make('particular_id')->hidden(),
                            Textarea::make('purpose')
                                ->label('Purpose')
                                ->required(),
                            TextInput::make('amount')
                                ->label('Amount')
                                ->numeric()
                                ->required(),
                        ])
                        ->minItems(1)
                        ->defaultItems(1),
                ])
                ->visible(function ($record) use ($adjustableSteps) {
                    if (! $record) {
                        return false;
                    }

                    return in_array($record->current_step_id, $adjustableSteps) && ! $record->for_cancellation && blank($record->pending_return_step_id);
                }),
        ];
    }
}
