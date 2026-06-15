<?php

    namespace App\Filament\Pages;

    use App\Models\CategoryItemBudget;
    use App\Models\DisbursementVoucher;
    use App\Models\DisbursementVoucherStep;
    use App\Models\FundCluster;
    use App\Models\Mop;
    use App\Models\User;
    use App\Models\VoucherSubType;
    use App\Services\DisbursementVouchers\DisbursementVoucherWorkflowService;
    use Awcodes\FilamentTableRepeater\Components\TableRepeater;
    use Filament\Forms\Components\DatePicker;
    use Filament\Forms\Components\Grid;
    use Filament\Forms\Components\Repeater;
    use Filament\Forms\Components\RichEditor;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\Textarea;
    use Filament\Forms\Components\TextInput;
    use Filament\Notifications\Notification;
    use Filament\Pages\Page;
    use Illuminate\Validation\ValidationException;

    class DisbursementVoucherFlowSimulator extends Page
    {
        protected static ?string $navigationGroup = 'Testing Tools';

        protected static ?string $navigationIcon = 'heroicon-o-switch-horizontal';

        protected static ?string $navigationLabel = 'DV Flow Simulator';

        protected static ?int $navigationSort = 1;

        protected static ?string $slug = 'disbursement-voucher-flow-simulator';

        protected static ?string $title = 'Disbursement Voucher Flow Simulator';

        protected static string $view = 'filament.pages.disbursement-voucher-flow-simulator';

        protected ?string $maxContentWidth = 'full';

        public ?int $selectedVoucherId = null;

        public array $selectVoucherData = [];

        public array $quickCreateData = [];

        public array $returnData = [];

        public array $releaseData = [];

        public array $relatedDocumentsData = [];

        public array $orsBursData = [];

        public array $accountingData = [];

        public array $chequeAdaData = [];

        public array $forwardData = [];

        public function mount(): void
        {
            abort_unless(auth()->user()?->canAccessFilament(), 403);

            $this->selectVoucherForm->fill();
            $this->quickCreateForm->fill($this->defaultQuickCreateData());
            $this->resetActionForms();
        }

        protected function getForms(): array
        {
            return [
                'selectVoucherForm' => $this->makeForm()
                    ->schema($this->selectVoucherFormSchema())
                    ->statePath('selectVoucherData'),
                'quickCreateForm' => $this->makeForm()
                    ->schema($this->quickCreateFormSchema())
                    ->statePath('quickCreateData'),
                'returnForm' => $this->makeForm()
                    ->schema($this->returnFormSchema())
                    ->statePath('returnData'),
                'releaseForm' => $this->makeForm()
                    ->schema($this->releaseFormSchema())
                    ->statePath('releaseData'),
                'relatedDocumentsForm' => $this->makeForm()
                    ->schema($this->relatedDocumentsFormSchema())
                    ->statePath('relatedDocumentsData'),
                'orsBursForm' => $this->makeForm()
                    ->schema($this->orsBursFormSchema())
                    ->statePath('orsBursData'),
                'accountingForm' => $this->makeForm()
                    ->schema($this->accountingFormSchema())
                    ->statePath('accountingData'),
                'chequeAdaForm' => $this->makeForm()
                    ->schema($this->chequeAdaFormSchema())
                    ->statePath('chequeAdaData'),
                'forwardForm' => $this->makeForm()
                    ->schema($this->forwardFormSchema())
                    ->statePath('forwardData'),
            ];
        }

        public static function shouldRegisterNavigation(): bool
        {
            return auth()->user()?->canAccessFilament() ?? false;
        }

        public function updatedSelectedVoucherId(): void
        {
            $this->selectVoucherData['voucher_id'] = $this->selectedVoucherId;
            $this->resetActionForms();
        }

        public function updatedQuickCreateDataRequisitionerId($value): void
        {
            $user = User::with('employee_information.office')->find($value);
            if (!$user) {
                return;
            }

            $this->quickCreateData['payee'] = $user->employee_information->full_name ?? $user->name;
            $this->quickCreateData['responsibility_center'] = $user->employee_information?->office?->name ?? 'Test Responsibility Center';
        }

        public function selectVoucher(): void
        {
            $data = $this->selectVoucherForm->getState();
            $this->selectedVoucherId = $data['voucher_id'] ?? null;
            $this->resetActionForms();
        }

        public function quickCreate(): void
        {
            $data = $this->quickCreateForm->getState();

            try {
                $voucher = app(DisbursementVoucherWorkflowService::class)->quickCreate($data);
                $this->selectedVoucherId = $voucher->id;
                $this->selectVoucherData['voucher_id'] = $voucher->id;
                $this->quickCreateForm->fill($this->defaultQuickCreateData());
                $this->resetActionForms();
                Notification::make()->title('Test disbursement voucher created.')->success()->send();
            } catch (ValidationException $exception) {
                throw $exception;
            } catch (\Throwable $exception) {
                report($exception);
                Notification::make()->title('Unable to create test DV.')->body($exception->getMessage())->danger()->send();
            }
        }

        public function receive(): void
        {
            $this->runWorkflowAction(fn(DisbursementVoucher $voucher) => app(DisbursementVoucherWorkflowService::class)->receive($voucher, auth()->user()), 'Document received.');
        }

        public function forward(): void
        {
            $data = $this->forwardForm->getState();
            $this->runWorkflowAction(fn(DisbursementVoucher $voucher) => app(DisbursementVoucherWorkflowService::class)->forward($voucher, auth()->user(), $data['remarks'] ?? null), 'Document forwarded.');
        }

        public function returnDocument(): void
        {
            $data = $this->returnForm->getState();
            $this->runWorkflowAction(fn(DisbursementVoucher $voucher) => app(DisbursementVoucherWorkflowService::class)->returnToStep($voucher, $data['return_step_id'], $data['remarks'] ?? null), 'DV marked for return.');
        }

        public function releaseReturn(): void
        {
            $data = $this->releaseForm->getState();
            $this->runWorkflowAction(fn(DisbursementVoucher $voucher) => app(DisbursementVoucherWorkflowService::class)->releaseReturn($voucher, auth()->user(), $data['release_log_number'], $data['release_note'] ?? null), 'Returned DV released.');
        }

        public function verifyRelatedDocuments(): void
        {
            $data = $this->relatedDocumentsForm->getState();
            $this->runWorkflowAction(fn(DisbursementVoucher $voucher) => app(DisbursementVoucherWorkflowService::class)->verifyRelatedDocuments($voucher, $data), 'Related documents verified.');
        }

        public function assignOrsBurs(): void
        {
            $data = $this->orsBursForm->getState();
            $this->runWorkflowAction(
                fn(DisbursementVoucher $voucher) => app(DisbursementVoucherWorkflowService::class)->assignOrsBurs($voucher, $data),
                'ORS/BURS details saved.',
                'orsBursData',
            );
        }

        public function recordAccounting(): void
        {
            $data = $this->accountingForm->getState();
            $this->runWorkflowAction(fn(DisbursementVoucher $voucher) => app(DisbursementVoucherWorkflowService::class)->recordAccounting($voucher, $data['dv_number'], $data['journal_date']), 'Accounting details recorded.');
        }

        public function certify(): void
        {
            $this->runWorkflowAction(fn(DisbursementVoucher $voucher) => app(DisbursementVoucherWorkflowService::class)->certify($voucher), 'Disbursement voucher certified.');
        }

        public function makeChequeAda(): void
        {
            $data = $this->chequeAdaForm->getState();
            $this->runWorkflowAction(fn(DisbursementVoucher $voucher) => app(DisbursementVoucherWorkflowService::class)->makeChequeAda($voucher, $data['mop_id'], $data['cheque_number']), 'Cheque/ADA recorded.');
        }

        public function getSelectedVoucherProperty(): ?DisbursementVoucher
        {
            if (!$this->selectedVoucherId) {
                return null;
            }

            return DisbursementVoucher::with([
                'current_step',
                'pending_return_step',
                'previous_step',
                'user.employee_information.office',
                'signatory.employee_information',
                'voucher_subtype.voucher_type',
                'voucher_subtype.related_documents_list',
                'disbursement_voucher_particulars',
                'uacs_allocations',
                'fund_cluster',
                'mop',
            ])->find($this->selectedVoucherId);
        }

        public function canReceive(): bool
        {
            $voucher = $this->selectedVoucher;

            return $voucher
                && $voucher->current_step?->process == 'Forwarded to'
                && !$voucher->for_cancellation
                && blank($voucher->pending_return_step_id);
        }

        public function canForward(): bool
        {
            $voucher = $this->selectedVoucher;

            return $voucher && app(DisbursementVoucherWorkflowService::class)->canBeForwarded($voucher);
        }

        public function canReturn(): bool
        {
            $voucher = $this->selectedVoucher;

            return $voucher
                && !$voucher->for_cancellation
                && blank($voucher->pending_return_step_id)
                && $voucher->current_step?->process != 'Forwarded to'
                && !empty(app(DisbursementVoucherWorkflowService::class)->returnStepOptions($voucher));
        }

        public function canReleaseReturn(): bool
        {
            return $this->selectedVoucher && filled($this->selectedVoucher->pending_return_step_id);
        }

        public function canVerifyRelatedDocuments(): bool
        {
            $voucher = $this->selectedVoucher;

            return $voucher
                && $voucher->current_step_id == 6000
                && !$voucher->for_cancellation
                && blank($voucher->pending_return_step_id)
                && $voucher->voucher_subtype?->related_documents_list
                && blank($voucher->related_documents);
        }

        public function canAssignOrsBurs(): bool
        {
            $voucher = $this->selectedVoucher;

            return $voucher
                && $voucher->current_step_id == 9000
                && !$voucher->for_cancellation
                && blank($voucher->pending_return_step_id);
        }

        public function canRecordAccounting(): bool
        {
            $voucher = $this->selectedVoucher;

            return $voucher
                && $voucher->current_step_id == 12000
                && blank($voucher->journal_date)
                && blank($voucher->dv_number)
                && !$voucher->for_cancellation
                && blank($voucher->pending_return_step_id);
        }

        public function canCertify(): bool
        {
            $voucher = $this->selectedVoucher;

            return $voucher
                && $voucher->current_step_id == 13000
                && !$voucher->certified_by_accountant
                && !$voucher->for_cancellation
                && blank($voucher->pending_return_step_id);
        }

        public function canMakeChequeAda(): bool
        {
            $voucher = $this->selectedVoucher;

            return $voucher
                && $voucher->current_step_id == 17000
                && blank($voucher->cheque_number)
                && !$voucher->for_cancellation
                && blank($voucher->pending_return_step_id);
        }

        public function returnOptions(): array
        {
            return $this->selectedVoucher
                ? app(DisbursementVoucherWorkflowService::class)->returnStepOptions($this->selectedVoucher)
                : [];
        }

        protected function selectVoucherFormSchema(): array
        {
            return [
                Select::make('voucher_id')
                    ->label('Existing Disbursement Voucher')
                    ->searchable()
                    ->preload()
                    ->options(fn() => DisbursementVoucher::query()
                        ->with(['user.employee_information', 'current_step'])
                        ->latest('submitted_at')
                        ->limit(50)
                        ->get()
                        ->mapWithKeys(fn(DisbursementVoucher $voucher) => [$voucher->id => $this->voucherOptionLabel($voucher)])
                        ->all())
                    ->getSearchResultsUsing(fn(string $search) => DisbursementVoucher::query()
                        ->with(['user.employee_information', 'current_step'])
                        ->where(function ($query) use ($search) {
                            $query
                                ->where('tracking_number', 'like', "%{$search}%")
                                ->orWhere('payee', 'like', "%{$search}%")
                                ->orWhere('dv_number', 'like', "%{$search}%")
                                ->orWhereHas('user.employee_information', fn($query) => $query->where('full_name', 'like', "%{$search}%"))
                                ->orWhereHas('current_step', fn($query) => $query->where('recipient', 'like', "%{$search}%")->orWhere('process', 'like', "%{$search}%"));
                        })
                        ->latest('submitted_at')
                        ->limit(50)
                        ->get()
                        ->mapWithKeys(fn(DisbursementVoucher $voucher) => [$voucher->id => $this->voucherOptionLabel($voucher)])
                        ->all())
                    ->getOptionLabelUsing(fn($value) => ($voucher = DisbursementVoucher::with(['user.employee_information', 'current_step'])->find($value)) ? $this->voucherOptionLabel($voucher) : null)
                    ->reactive(),
            ];
        }

        protected function quickCreateFormSchema(): array
        {
            return [
                Grid::make(2)->schema([
                    Select::make('requisitioner_id')
                        ->label('Requisitioner')
                        ->options(fn() => $this->userOptions())
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->required(),
                    Select::make('voucher_subtype_id')
                        ->label('Voucher Subtype')
                        ->options(fn() => VoucherSubType::with('voucher_type')->get()->mapWithKeys(fn(VoucherSubType $subtype) => [
                            $subtype->id => trim(($subtype->voucher_type->name ?? 'Voucher').' - '.$subtype->name),
                        ]))
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('signatory_id')
                        ->label('Signatory')
                        ->options(fn() => $this->userOptions())
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('mop_id')
                        ->label('Mode of Payment')
                        ->options(fn() => Mop::pluck('name', 'id'))
                        ->searchable()
                        ->preload(),
                    TextInput::make('payee')
                        ->required(),
                    TextInput::make('responsibility_center')
                        ->required(),
                    DatePicker::make('activity_date_from')
                        ->required(),
                    DatePicker::make('activity_date_to')
                        ->required(),
                ]),
                Repeater::make('particulars')
                    ->schema([
                        Textarea::make('purpose')->required(),
                        TextInput::make('mfo_pap')->required(),
                        TextInput::make('amount')->numeric()->minValue(0.01)->required(),
                    ])
                    ->columns(3)
                    ->minItems(1)
                    ->required(),
            ];
        }

        protected function returnFormSchema(): array
        {
            return [
                Select::make('return_step_id')
                    ->label('Return to')
                    ->options(fn() => $this->returnOptions())
                    ->required(),
                RichEditor::make('remarks')
                    ->label('Remarks'),
            ];
        }

        protected function releaseFormSchema(): array
        {
            return [
                TextInput::make('release_log_number')
                    ->label('Log Number')
                    ->default('TEST-'.now()->format('Ymd-His'))
                    ->required(),
                Textarea::make('release_note')
                    ->label('Note'),
            ];
        }

        protected function relatedDocumentsFormSchema(): array
        {
            return [
                TextInput::make('log_number')
                    ->label('Log Number')
                    ->default('TEST-'.now()->format('Ymd-His')),
                Repeater::make('items')
                    ->schema([
                        TextInput::make('document')->disabled()->required(),
                        Select::make('status')
                            ->options([
                                'required' => 'Required',
                                'not_required' => 'For Compliance',
                                'not_applicable' => 'Not Applicable',
                            ])
                            ->default('required')
                            ->required(),
                        TextInput::make('remarks'),
                    ])
                    ->columns(3),
                RichEditor::make('remarks')
                    ->label('General Remarks'),
            ];
        }

        protected function orsBursFormSchema(): array
        {
            return [
                Grid::make(3)->schema([
                    Select::make('fund_cluster_id')
                        ->label('Fund Cluster')
                        ->options(fn() => FundCluster::whereIn('id', [1, 2, 3, 8])->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('ors_burs')
                        ->label('ORS/BURS')
                        ->required(),
                    TextInput::make('responsibility_center')
                        ->required(),
                ]),
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
                            ->options(fn() => CategoryItemBudget::selectRaw("id, concat(uacs_code, ' - ', name) as code")->pluck('code', 'id'))
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
        }

        protected function accountingFormSchema(): array
        {
            return [
                Grid::make(2)->schema([
                    TextInput::make('dv_number')->required(),
                    DatePicker::make('journal_date')->required(),
                ]),
            ];
        }

        protected function chequeAdaFormSchema(): array
        {
            return [
                Grid::make(2)->schema([
                    Select::make('mop_id')
                        ->label('Mode of Payment')
                        ->options(fn() => Mop::pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('cheque_number')
                        ->label('Cheque number/ADA')
                        ->required(),
                ]),
            ];
        }

        protected function forwardFormSchema(): array
        {
            return [
                RichEditor::make('remarks')
                    ->label('Remarks'),
            ];
        }

        private function runWorkflowAction(callable $callback, string $successMessage, ?string $errorStatePath = null): void
        {
            $voucher = $this->selectedVoucher;

            if (!$voucher) {
                Notification::make()->title('Select a disbursement voucher first.')->warning()->send();
                return;
            }

            try {
                $callback($voucher);
                $this->resetActionForms();
                Notification::make()->title($successMessage)->success()->send();
            } catch (ValidationException $exception) {
                if ($errorStatePath) {
                    foreach ($exception->errors() as $field => $messages) {
                        foreach ($messages as $message) {
                            $this->addError($errorStatePath.'.'.$field, $message);
                        }
                    }

                    Notification::make()->title('Please check the highlighted fields.')->danger()->send();
                    return;
                }

                throw $exception;
            } catch (\Throwable $exception) {
                report($exception);
                Notification::make()->title('Workflow action failed.')->body($exception->getMessage())->danger()->send();
            }
        }

        private function resetActionForms(): void
        {
            $voucher = $this->selectedVoucher;

            $this->returnForm->fill([
                'return_step_id' => $voucher ? array_key_first($this->returnOptions()) : null,
                'remarks' => null,
            ]);
            $this->releaseForm->fill([
                'release_log_number' => 'TEST-'.now()->format('Ymd-His'),
                'release_note' => null,
            ]);
            $this->relatedDocumentsForm->fill([
                'log_number' => 'TEST-'.now()->format('Ymd-His'),
                'items' => $voucher ? $this->defaultRelatedDocuments($voucher) : [],
                'remarks' => null,
            ]);
            $this->orsBursForm->fill([
                'fund_cluster_id' => $voucher->fund_cluster_id ?? FundCluster::whereIn('id', [1, 2, 3, 8])->value('id'),
                'ors_burs' => $voucher ? ($voucher->ors_burs ?? 'TEST-ORS-'.now()->format('Ymd-His')) : null,
                'responsibility_center' => $voucher ? ($voucher->responsibility_center ?? 'Test Responsibility Center') : null,
                'uacs_allocations' => $voucher ? app(DisbursementVoucherWorkflowService::class)->defaultUacsAllocations($voucher) : [],
            ]);
            $this->accountingForm->fill([
                'dv_number' => $voucher ? ($voucher->dv_number ?? 'TEST-DV-'.now()->format('Ymd-His')) : null,
                'journal_date' => now()->format('Y-m-d'),
            ]);
            $this->chequeAdaForm->fill([
                'mop_id' => $voucher->mop_id ?? Mop::query()->value('id'),
                'cheque_number' => 'TEST-ADA-'.now()->format('Ymd-His'),
            ]);
            $this->forwardForm->fill([
                'remarks' => null,
            ]);
        }

        private function defaultQuickCreateData(): array
        {
            $requisitioner = User::with('employee_information.office')->whereHas('employee_information')->first() ?? auth()->user();
            $signatory = User::whereHas('employee_information')->whereHas('signature')->first()
                ?? User::whereHas('employee_information')->first()
                ?? auth()->user();
            $voucherSubtype = VoucherSubType::whereNotIn('id', array_merge(VoucherSubType::TRAVELS, [VoucherSubType::ACTIVITY_DESIGN]))->first()
                ?? VoucherSubType::first();

            return [
                'requisitioner_id' => $requisitioner?->id,
                'voucher_subtype_id' => $voucherSubtype?->id,
                'signatory_id' => $signatory?->id,
                'mop_id' => Mop::query()->value('id'),
                'payee' => $requisitioner?->employee_information?->full_name ?? $requisitioner?->name,
                'responsibility_center' => $requisitioner?->employee_information?->office?->name ?? 'Test Responsibility Center',
                'activity_date_from' => now()->format('Y-m-d'),
                'activity_date_to' => now()->addDays(3)->format('Y-m-d'),
                'particulars' => [
                    [
                        'purpose' => 'Testing disbursement voucher workflow',
                        'mfo_pap' => 'TEST-MFO',
                        'amount' => '1000.00',
                    ]
                ],
            ];
        }

        private function defaultRelatedDocuments(DisbursementVoucher $voucher): array
        {
            $documents = $voucher->voucher_subtype?->related_documents_list?->documents ?? [];

            return collect($documents)->map(fn($document) => [
                'document' => $document,
                'status' => 'required',
                'remarks' => null,
            ])->values()->all();
        }

        private function userOptions(): array
        {
            return User::with('employee_information')
                ->whereHas('employee_information')
                ->get()
                ->mapWithKeys(fn(User $user) => [$user->id => $user->employee_information->full_name ?? $user->email])
                ->all();
        }

        private function voucherOptionLabel(DisbursementVoucher $voucher): string
        {
            $step = trim(($voucher->current_step->process ?? '').' '.($voucher->current_step->recipient ?? ''));
            $requisitioner = $voucher->user->employee_information->full_name ?? 'Unknown requisitioner';

            return "{$voucher->tracking_number} - {$requisitioner} ({$step})";
        }
    }
