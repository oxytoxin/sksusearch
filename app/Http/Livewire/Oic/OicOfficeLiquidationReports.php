<?php

    namespace App\Http\Livewire\Oic;

    use App\Forms\Components\Flatpickr;
    use App\Models\DisbursementVoucherStep;
    use App\Models\EmployeeInformation;
    use App\Models\LiquidationReport;
    use App\Models\LiquidationReportStep;
    use App\Models\OicUser;
    use App\Models\User;
    use Filament\Forms\Components\CheckboxList;
    use Filament\Forms\Components\Fieldset;
    use Filament\Forms\Components\RichEditor;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Filament\Notifications\Notification;
    use Filament\Tables\Actions\Action;
    use Filament\Tables\Actions\ActionGroup;
    use Filament\Tables\Actions\ViewAction;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Tables\Filters\Layout;
    use Filament\Tables\Filters\SelectFilter;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class OicOfficeLiquidationReports extends Component implements HasTable
    {
        use InteractsWithTable;

        public function isOic()
        {
            return true;
        }

        protected function getTableQuery()
        {
            return LiquidationReport::latest('report_date');
        }

        public function getTableColumns()
        {
            return [
                TextColumn::make('tracking_number'),
                TextColumn::make('disbursement_voucher.tracking_number'),
                TextColumn::make('report_date')->date()->label('Date'),
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
                        $query->whereRelation('current_step', 'office_group_id', '=', User::find($state)?->first()?->employee_information->office->office_group_id ?? -777);
                    }),
                SelectFilter::make('for_cancellation')->options([
                    true => 'For Cancellation',
                    false => 'For Approval',
                ])->default(0)->label('Status'),
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

        public function getTableActions()
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
                            'description' => $record->current_step->process.' '.auth()->user()->employee_information->full_name.' (OIC)',
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
                    ->visible(fn($record) => filled($record) && $record->current_step->process == 'Forwarded to' && $record->for_cancellation == false)
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
                        'description' => $record->current_step->process.' '.$record->current_step->recipient.' by OIC: '.auth()->user()->employee_information->full_name,
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
                    ->visible(fn($record) => filled($record) && $record->certified_by_accountant && !$record->for_cancellation)
                    ->requiresConfirmation(),
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
                    $record->activity_logs()->create([
                        'description' => 'Liquidation Report verified by OIC: '.auth()->user()->employee_information->full_name.'.',
                    ]);
                    DB::commit();
                    Notification::make()->title('Liquidation Report verified.')->success()->send();
                })
                    ->visible(fn($record) => filled($record) && $record->current_step_id == 7000 && blank($record->journal_date) && blank($record->lr_number) && !$record->for_cancellation)
                    ->form(function () {
                        return [
                            TextInput::make('lr_number')->label('LR Number')->required(),
                            Flatpickr::make('journal_date')->disableTime()->required(),
                            Fieldset::make('Related Documents')
                                ->columns(1)
                                ->schema([
                                    CheckboxList::make('verified_documents')
                                        ->options(function ($record) {
                                            return collect($record?->disbursement_voucher->voucher_subtype->related_documents_list?->liquidation_report_documents)->flatMap(fn($d) => [$d => $d]) ?? [];
                                        }),
                                    RichEditor::make('remarks'),
                                ]),
                        ];
                    })
                    ->modalWidth('3xl')
                    ->requiresConfirmation(),
                Action::make('certify')->button()->action(function ($record) {
                    DB::beginTransaction();
                    $record->update([
                        'certified_by_accountant' => true,
                    ]);
                    $record->recordAccountantApproval($this->tableFilters['as']['value'] ?? auth()->id(), auth()->id());
                    $record->activity_logs()->create([
                        'description' => 'Liquidation Report certified by OIC: '.auth()->user()->employee_information->full_name.'.',
                    ]);
                    DB::commit();
                    Notification::make()->title('Liquidation Report certified.')->success()->send();
                })
                    ->visible(fn($record, $livewire) => filled($record) && $record->current_step_id == 8000 && !$record->for_cancellation && !$record->certified_by_accountant && User::find($livewire->tableFilters['as']['value'] ?? 0)?->employee_information?->position_id == User::find($livewire->tableFilters['as']['value'] ?? 0)?->employee_information?->office?->head_position_id)
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
                        'description' => 'Liquidation Report returned to '.$record->current_step->recipient.' by OIC: '.auth()->user()->employee_information->full_name,
                        'remarks' => $data['remarks'] ?? null,
                    ]);
                    DB::commit();
                    Notification::make()->title('Liquidation Report returned.')->success()->send();
                })
                    ->color('danger')
                    ->visible(fn($record) => filled($record) && $record->current_step_id == 4000 && $record->for_cancellation == false)
                    ->form(function () {
                        return [
                            Select::make('return_step_id')
                                ->label('Return to')
                                ->options(fn($record) => LiquidationReportStep::where('process', 'Forwarded to')->where('recipient', '!=', $record->current_step->recipient)->where('id', '<', $record->current_step_id)->pluck('recipient', 'id'))
                                ->required(),
                            RichEditor::make('remarks')->label('Remarks (Optional)')->fileAttachmentsDisk('remarks'),
                        ];
                    })
                    ->modalWidth('4xl')
                    ->requiresConfirmation(),
                ActionGroup::make([
                    ViewAction::make('progress')
                        ->label('Progress')
                        ->icon('ri-loader-4-fill')
                        ->modalHeading('Liquidation Report Progress')
                        ->modalContent(fn($record) => view('components.timeline_views.progress_logs', [
                            'record' => $record,
                            'steps' => LiquidationReportStep::whereEnabled(true)->where('id', '>', 2000)->get(),
                        ])),
                    ViewAction::make('view')
                        ->label('Preview')
                        ->openUrlInNewTab()
                        ->url(fn($record) => route('signatory.liquidation-reports.show', ['liquidation_report' => $record]), true),
                ])->icon('ri-eye-line'),
            ];
        }

        public function render()
        {
            return view('livewire.oic.oic-office-liquidation-reports');
        }
    }
