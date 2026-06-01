<?php

    namespace App\Http\Livewire\Offices\Traits;

    use App\Models\CategoryItemBudget;
    use App\Models\FundCluster;
    use App\Models\Mop;
    use App\Models\TravelOrderType;
    use Filament\Forms\Components\Grid;
    use Filament\Forms\Components\Placeholder;
    use Filament\Forms\Components\Textarea;
    use Illuminate\Support\Facades\DB;
    use App\Forms\Components\Flatpickr;
    use App\Http\Controllers\NotificationController;
    use App\Models\DisbursementVoucher;
    use App\Models\EmployeeInformation;
    use Filament\Tables\Actions\Action;
    use Illuminate\Support\Facades\Auth;
    use Filament\Forms\Components\Select;
    use App\Models\DisbursementVoucherStep;
    use App\Notifications\SubmissionRequestNotification;
    use Filament\Tables\Actions\EditAction;
    use Filament\Tables\Actions\ViewAction;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Forms\Components\TextInput;
    use Filament\Notifications\Notification;
    use Filament\Tables\Actions\ActionGroup;
    use Filament\Forms\Components\RichEditor;
    use App\Forms\Components\RelatedDocumentsChecklist;
    use Carbon\Carbon;
    use App\Jobs\SendSmsJob;

    trait OfficeDashboardActions
    {
        public bool $icuReturnCameFromVerify = false;

        public function isOic()
        {
            return false;
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
            if (!$record) {
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
                TextColumn::make('tracking_number')->searchable(),
                TextColumn::make('voucher_subtype.voucher_type.name')->limit(20)->tooltip(fn($record) => $record->voucher_subtype->voucher_type->name)->label('Voucher Type'),
                TextColumn::make('user.employee_information.full_name')->label('Requisitioner'),
                TextColumn::make('payee')
                    ->limit(10)
                    ->tooltip(fn($record) => $record->payee)
                    ->label('Payee'),
                TextColumn::make('submitted_at')->dateTime('F d, Y'),
                TextColumn::make('disbursement_voucher_particulars_sum_amount')->sum('disbursement_voucher_particulars', 'amount')->label('Amount')->money('php', true),
            ];
        }

        private function canBeForwarded($record)
        {
            if (!$record) {
                Notification::make()->title('Selected document not found in office.')->warning()->send();
                return;
            }
            return ($record->current_step->process == 'Received in' && !in_array($record->current_step_id, [6000, 9000, 13000, 17000]))
                || ($record->current_step_id == 9000 && filled($record->ors_burs) && filled($record->fund_cluster_id))
                || ($record->current_step_id == 12000 && filled($record->journal_date) && filled($record->dv_number))
                || ($record->current_step_id == 13000 && $record->certified_by_accountant)
                || ($record->current_step_id == 18000 && filled($record->cheque_number))
                || ($record->current_step_id == 6000 && (!$record->voucher_subtype->related_documents_list || $record->hasCompletedRelatedDocumentsVerification()));
        }

        private function viewActions()
        {
            return [
                ActionGroup::make([
                    ViewAction::make('progress')
                        ->label('Progress')
                        ->icon('ri-loader-4-fill')
                        ->modalHeading('Disbursement Voucher Progress')
                        ->modalContent(fn($record) => view('components.timeline_views.progress_logs', [
                            'record' => $record,
                            'steps' => DisbursementVoucherStep::whereEnabled(true)->where('id', '>', 2000)->get(),
                        ])),
                    ViewAction::make('logs')
                        ->label('Activity Timeline')
                        ->icon('ri-list-check-2')
                        ->modalHeading('Disbursement Voucher Activity Timeline')
                        ->modalContent(fn($record) => view('components.timeline_views.activity_logs', [
                            'record' => $record,
                        ])),
                    ViewAction::make('related_documents')
                        ->label('Related Documents')
                        ->icon('ri-file-copy-2-line')
                        ->modalHeading('Disbursement Voucher Related Documents')
                        ->modalContent(fn($record) => view('components.disbursement_vouchers.disbursement_voucher_documents', [
                            'disbursement_voucher' => $record,
                        ])),
                    ViewAction::make('ctc')
                        ->label('Certificate of Travel Completion')
                        ->icon('ri-file-text-line')
                        ->url(fn($record) => route('ctc.show', ['ctc' => $record->travel_completed_certificate]), true)
                        ->visible(fn($record) => $record->travel_completed_certificate()->exists()),
                    ViewAction::make('actual_itinerary')
                        ->label('Actual Itinerary')
                        ->icon('ri-file-copy-line')
                        ->url(fn($record) => route('signatory.itinerary.print', ['itinerary' => $record->travel_order->itineraries()->where('user_id', $record->user_id)->whereIsActual(true)->first()]), true)
                        ->visible(fn($record) => $record->travel_order?->travel_order_type_id == TravelOrderType::OFFICIAL_BUSINESS && $record->travel_order?->itineraries()->where('user_id', $record->user_id)->whereIsActual(true)->exists()),
                    ViewAction::make('view')
                        ->label('Preview')
                        ->openUrlInNewTab()
                        ->url(fn($record) => route('disbursement-vouchers.show', ['disbursement_voucher' => $record]), true),
                ])->icon('ri-eye-line'),
            ];
        }

        private function icuActions()
        {
            return [
                EditAction::make()
                    ->button()
                    ->icon('ri-file-copy-2-line')
                    ->label('Verify Related Documents')
                    ->modalHeading('Verify Related Documents')
                    ->modalWidth('4xl')
                    ->mountUsing(function ($form, $record) {
                        $documents = $record?->voucher_subtype?->related_documents_list?->documents ?? [];
                        $form->fill([
                            'log_number' => $record->log_number,
                            'items' => collect($documents)->map(fn($doc) => [
                                'document' => $doc,
                                'status' => 'required',
                                'remarks' => null,
                            ])->values()->all(),
                            'remarks' => $record->related_documents['remarks'] ?? null,
                        ]);
                    })
                    ->action(function ($record, $data) {
                        $record->refresh();
                        DB::beginTransaction();
                        $record->update([
                            'log_number' => $data['log_number'],
                            'documents_verified_at' => now(),
                            'related_documents' => [
                                'items' => collect($data['items'] ?? [])->map(fn($item) => [
                                    'document' => $item['document'] ?? '',
                                    'status' => $item['status'] ?? 'required',
                                    'remarks' => $item['remarks'] ?? null,
                                ])->values()->all(),
                                'remarks' => $data['remarks'] ?? '',
                            ]
                        ]);
                        $description = 'Related documents have been verified.';
                        if ($this->isOic()) {
                            $description .= "\nOIC: ".auth()->user()->employee_information->full_name.'.';
                        }
                        $record->activity_logs()->create([
                            'description' => $description,
                        ]);
                        DB::commit();
                        Notification::make()->title('Related documents have been verified.')->success()->send();
                    })
                    ->form([
                        TextInput::make('log_number')
                            ->required(),
                        RelatedDocumentsChecklist::make('items')
                            ->label('Documentary Requirements')
                            ->documents(fn($record) => $record?->voucher_subtype?->related_documents_list?->documents ?? [])
                            ->required()
                            ->rule(function () {
                                return function (string $attribute, $value, \Closure $fail) {
                                    if (!is_array($value)) {
                                        $fail('Invalid checklist data.');
                                        return;
                                    }
                                    foreach ($value as $i => $item) {
                                        $status = $item['status'] ?? null;
                                        if (!in_array($status, ['required', 'not_required', 'not_applicable'])) {
                                            $fail('Each document must have a status set.');
                                            return;
                                        }
                                        if (in_array($status, ['not_required', 'not_applicable']) && blank($item['remarks'] ?? null)) {
                                            $fail('A note is required for "' . ($item['document'] ?? 'unknown') . '" since it is marked as ' . str_replace('_', ' ', $status) . '.');
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
                        if (!$record) {
                            Notification::make()->title('Selected document not found in office.')->warning()->send();
                            return false;
                        }
                        return $record->current_step_id == 6000 && $record->for_cancellation == false && $record->voucher_subtype->related_documents_list && blank($record->related_documents);
                    }),

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
                                ->options(fn($record) => DisbursementVoucherStep::where('process', 'Forwarded to')->where('recipient', '!=', $record->current_step->recipient)->where('id', '<', $record->current_step_id)->pluck('recipient', 'id'))
                                ->required(),
                            RichEditor::make('remarks')
                                ->label('Return Reason')
                                ->required()
                                ->fileAttachmentsDisk('remarks'),
                        ];
                    })
                    ->action(function ($record, $data) {
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
                        $description = 'Disbursement Voucher returned to ' . $record->current_step->recipient . ' by ICU.';
                        if ($this->isOic()) {
                            $description .= "\nOIC: ".auth()->user()->employee_information->full_name.'.';
                        }
                        $record->activity_logs()->create([
                            'description' => $description,
                            'remarks' => $data['remarks'] ?? null,
                        ]);
                        DB::commit();

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

                        Notification::make()->title('Disbursement Voucher returned.')->success()->send();
                    })
                    ->visible(function ($record) {
                        if (!$record) {
                            return false;
                        }
                        return $record->current_step_id == 6000 && $record->for_cancellation == false;
                    }),
            ];
        }

        private function cashierActions()
        {
            return [
                Action::make('cheque_ada')->label('Cheque/ADA')->button()->action(function ($record, $data) {
                    DB::beginTransaction();
                    $record->update([
                        'mop_id' => $data['mop_id'],
                        'cheque_number' => $data['cheque_number'],
                        'current_step_id' => $record->current_step_id + 1000,
                        'cheque_number_added_at' => now(),
                    ]);
                    $description = 'Cheque/ADA made for requisitioner.';
                    if ($this->isOic()) {
                        $description .= "\nOIC: ".auth()->user()->employee_information->full_name.'.';
                    }
                    $record->activity_logs()->create([
                        'description' => $description,
                    ]);
                    $end_date = null;
                    $liquidation_period_end_date = null;
                    switch ($record->voucher_subtype_id) {
                        case 1:
                            //local travel
                            $end_date = $record->travel_order()->exists() ? $record->travel_order->date_to : $record->other_details['activity_date_to'] ?? null;
                            $liquidation_period_end_date = Carbon::parse($end_date)->addDays(30)->format('Y-m-d');
                            break;
                        case 2:
                            //foreign travel
                            $end_date = $record->travel_order()->exists() ? $record->travel_order->date_to : $record->other_details['activity_date_to'] ?? null;
                            $liquidation_period_end_date = Carbon::parse($end_date)->addDays(60)->format('Y-m-d');
                            break;
                        case 3:
                            //activities
                            $end_date = $record->travel_order()->exists() ? $record->travel_order->date_to : $record->other_details['activity_date_to'] ?? null;
                            $liquidation_period_end_date = Carbon::parse($end_date)->addDays(20)->format('Y-m-d');
                            break;
                        case 4:
                            //payroll
                            $end_date = $record->travel_order()->exists() ? $record->travel_order->date_to : $record->other_details['activity_date_to'] ?? null;
                            $liquidation_period_end_date = Carbon::parse($end_date)->addDays(5)->format('Y-m-d');
                            break;
                        case 5:
                            //special disbursing officer
                            $end_date = $record->travel_order()->exists() ? $record->travel_order->date_to : $record->other_details['activity_date_to'] ?? null;
                            $liquidation_period_end_date = Carbon::parse($end_date)->addDays(5)->format('Y-m-d');
                            break;
                        default:
                            $end_date = null;
                            $liquidation_period_end_date = null;
                            break;
                    }

                    $record->cash_advance_reminder()->create([
                        'status' => 'On-Going',
                        'voucher_end_date' => $end_date,
                        'liquidation_period_end_date' => $liquidation_period_end_date,
                        'step' => 1,
                        'is_sent' => false,
                        'title' => 'Send FMR',
                        'message' => 'Ongoing liquidation of cash advance.',
                    ]);


                    $receiver = $record->user;
                    NotificationController::cashAdvanceCreation(Auth::user(), $receiver, $record);

                    // ========== SMS NOTIFICATION ==========
                    // Send SMS notification
                    $record->load(['user.employee_information']);
                    $trackingNumber = $record->tracking_number;
                    $chequeNumber = $data['cheque_number'];
                    $message = "Your DV with ref. no. {$trackingNumber} is ready for disbursement with check/ADA number {$chequeNumber}.";

                    $requestedBy = $record->user;
                    if ($requestedBy && $requestedBy->employee_information && !empty($requestedBy->employee_information->contact_number)) {
                        SendSmsJob::dispatch(
                            $requestedBy->employee_information->contact_number,
                            $message,
                            'disbursement_voucher_ready',
                            $requestedBy->id,
                            auth()->id()
                        );
                    }
                    // ========== SMS NOTIFICATION END ==========

                    DB::commit();
                    Notification::make()->title('Cheque/ADA made for requisitioner.')->success()->send();
                })
                    ->visible(function ($record) {
                        if (!$record) {
                            Notification::make()->title('Selected document not found in office.')->warning()->send();
                            return false;
                        }
                        return $record->current_step_id == 17000 && blank($record->cheque_number) && $record->for_cancellation == false;
                    })
                    ->form(function () {
                        return [
                            Grid::make(2)
                                ->schema([
                                    Placeholder::make('dv_number')->label('DV Number')->content(fn($record) => $record->dv_number ?? 'N/A'),
                                    Placeholder::make('ors_burs')->label('ORS/BURS')->content(fn($record) => $record->ors_burs ?? 'N/A'),
                                ]),
                            Placeholder::make('fund_cluster')->label('Fund Cluster')->content(fn($record) => $record->fund_cluster->name ?? 'N/A'),
                            TextInput::make('cheque_number')
                                ->label('Cheque number/ADA')
                                ->required(),
                            Select::make('mop_id')
                                ->label('Mode of Payment')
                                ->options(Mop::pluck('name', 'id'))
                                ->required()
                        ];
                    })
                    ->requiresConfirmation(),
                Action::make('cancel')
                    ->requiresConfirmation()
                    ->action(function ($record, $data) {
                        $record->update([
                            'cancellation_remarks' => $data['cancellation_remarks'],
                            'for_cancellation' => true,
                            'cancelled_at' => now()
                        ]);
                        $description = 'Cheque/ADA cancelled.';
                        $record->activity_logs()->create([
                            'description' => $description,
                        ]);
                    })
                    ->form([
                        Textarea::make('cancellation_remarks')
                    ])
                    ->button()
                    ->color('danger')
                    ->visible(fn($record) => $record->cheque_number && $record->current_step_id == 18000 && !$record->for_cancellation),
            ];
        }

        private function accountingActions()
        {
            return [
                Action::make('verify')->button()->action(function ($record, $data) {
                    DB::beginTransaction();
                    $record->update([
                        'dv_number' => $data['dv_number'],
                        'journal_date' => $data['journal_date'],
                    ]);
                    $record->refresh();
                    $description = 'Disbursement Voucher verified.';
                    if ($this->isOic()) {
                        $description .= "\nOIC: ".auth()->user()->employee_information->full_name.'.';
                    }
                    $record->activity_logs()->create([
                        'description' => $description,
                    ]);
                    DB::commit();
                    Notification::make()->title('Disbursement Voucher verified.')->success()->send();
                })
                    ->visible(function ($record) {
                        if (!$record) {
                            Notification::make()->title('Selected document not found in office.')->warning()->send();
                            return false;
                        }
                        return $record->current_step_id == 12000 && blank($record->journal_date) && blank($record->dv_number) && $record->for_cancellation == false;
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
                    DB::beginTransaction();
                    $record->update([
                        'ors_burs' => $data['ors_burs'],
                        'category_item_budget_id' => $data['category_item_budget_id'],
                        'responsibility_center' => $data['responsibility_center'],
                        'fund_cluster_id' => $data['fund_cluster_id'],
                    ]);
                    $description = 'ORS/BURS and Fund Cluster assigned to Disbursement Voucher.';
                    if ($this->isOic()) {
                        $description .= "\nOIC: ".auth()->user()->employee_information->full_name.'.';
                    }
                    $record->activity_logs()->create([
                        'description' => $description,
                    ]);
                    DB::commit();
                    Notification::make()->title('ORS/BURS and Fund Cluster updated.')->success()->send();
                })
                    ->visible(function ($record) {
                        if (!$record) {
                            Notification::make()->title('Selected document not found in office.')->warning()->send();
                            return false;
                        }
                        return $record->current_step_id == 9000 && (blank($record->ors_burs) || blank($record->fund_cluster_id)) && $record->for_cancellation == false;
                    })
                    ->form(function ($record) {
                        return [
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
                            Select::make('category_item_budget_id')
                                ->label('UACS Code')
                                ->options(CategoryItemBudget::selectRaw("id, concat(uacs_code, ' - ', name) as code")->pluck('code', 'id'))
                                ->preload()
                                ->searchable()
                                ->required()
                        ];
                    })
                    ->requiresConfirmation(),
            ];
        }

        private function commonActions()
        {
            return [
                Action::make('Receive')->button()->action(function (DisbursementVoucher $record) {
                    if ($record->current_step->process == 'Forwarded to') {
                        DB::beginTransaction();
                        $record->update([
                            'current_step_id' => $record->current_step->next_step->id,
                        ]);
                        $record->refresh();
                        $description = $record->current_step->process.' '.$record->current_step->recipient.' by ';
                        if ($this->isOic()) {
                            $description .= "OIC: ".auth()->user()->employee_information->full_name.'.';
                        } else {
                            $description .= auth()->user()->employee_information->full_name;
                        }
                        $record->activity_logs()->create([
                            'description' => $description,
                        ]);
                        if ($record->current_step_id == 8000 || $record->current_step_id == 11000) {
                            $record->update([
                                'current_step_id' => $record->current_step_id + 1000,
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
                            Notification::make()->title('Selected document not found in office.')->warning()->send();
                            return false;
                        }
                        return $record->current_step->process == 'Forwarded to' && $record->for_cancellation == false;
                    })
                    ->requiresConfirmation(),
                Action::make('Forward')->button()->action(function ($record, $data) {
                    if ($this->canBeForwarded($record)) {
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
                        if ($record->current_step_id == 13000) {
                            $record->activity_logs()->create([
                                'description' => $record->current_step->process.' '.$record->current_step->recipient,
                                'remarks' => $data['remarks'] ?? null,
                            ]);
                        } else {
                            $description = $record->current_step->process.' '.$record->current_step->recipient.' by ';
                            if ($this->isOic()) {
                                $description .= "OIC: ".auth()->user()->employee_information->full_name.'.';
                            } else {
                                $description .= auth()->user()->employee_information->full_name;
                            }
                            $record->activity_logs()->create([
                                'description' => $description,
                                'remarks' => $data['remarks'] ?? null,
                            ]);
                        }

                        // ========== SMS NOTIFICATION ==========
                        // Notify the requisitioner that their DV was approved and forwarded
                        $record->load(['user.employee_information', 'current_step']);
                        $trackingNumber = $record->tracking_number;
                        $officerName = auth()->user()->employee_information->full_name ?? 'Officer';
                        $nextRecipient = $record->current_step->recipient ?? 'the next office';
                        $approverPrefix = $this->isOic() ? 'OIC ' : '';
                        $message = "Your DV with ref. no. {$trackingNumber} has been approved by {$approverPrefix}{$officerName} and forwarded to {$nextRecipient}.";

                        $requestedBy = $record->user;
                        if ($requestedBy && $requestedBy->employee_information && !empty($requestedBy->employee_information->contact_number)) {
                            SendSmsJob::dispatch(
                                $requestedBy->employee_information->contact_number,
                                $message,
                                'disbursement_voucher_forwarded',
                                $requestedBy->id,
                                auth()->id()
                            );
                        }
                        // ========== SMS NOTIFICATION END ==========

                        DB::commit();
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
                    ->visible(fn($record) => $this->canBeForwarded($record) && $record->for_cancellation == false)
                    ->requiresConfirmation(),
            ];
        }
    }
