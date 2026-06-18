<?php

    namespace App\Http\Livewire\Oic;

    use App\Models\EmployeeInformation;
    use App\Models\LiquidationReport;
    use App\Models\LiquidationReportStep;
    use App\Models\OicUser;
    use Filament\Forms\Components\Placeholder;
    use Filament\Forms\Components\RichEditor;
    use Filament\Notifications\Notification;
    use Filament\Tables\Actions\Action;
    use Filament\Tables\Actions\ActionGroup;
    use Filament\Tables\Actions\ViewAction;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Tables\Filters\SelectFilter;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\HtmlString;
    use Livewire\Component;

    class OicSignatoryLiquidationReports extends Component implements HasTable
    {
        use InteractsWithTable;

        protected function getTableQuery()
        {
            return LiquidationReport::whereNull('cancelled_at')->latest('report_date');
        }

        protected function getTableColumns()
        {
            return [
                TextColumn::make('tracking_number'),
                TextColumn::make('disbursement_voucher.tracking_number'),
                TextColumn::make('report_date')->date()->label('Date'),
                TextColumn::make('status')->formatStateUsing(fn($record) => $record->for_cancellation ? ($record->cancelled_at ? 'Cancelled' : 'For Cancellation') : (($record->current_step_id > 4000 || $record->previous_step_id > 4000) ? 'Signed' : 'To Sign')),
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
                        $query->where('signatory_id', $state);
                    }),
                SelectFilter::make('for_cancellation')->options([
                    true => 'For Cancellation',
                    false => 'For Approval',
                ])->default(0)->label('Status'),
            ];
        }

        protected function getTableFiltersLayout(): ?string
        {
            return \Filament\Tables\Filters\Layout::AboveContent;
        }

        protected function getTableFiltersFormColumns(): int
        {
            return 2;
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
                        DB::commit();
                        Notification::make()->title('Document Received')->success()->send();
                    }
                })
                    ->visible(fn($record) => filled($record) && $record->current_step_id == 3000 && $record->for_cancellation == false)
                    ->requiresConfirmation(),
                Action::make('Forward')->button()->action(function ($record, $data) {
                    DB::beginTransaction();
                    if ($record->disbursement_voucher->travel_order_id) {
                        $actual_itinerary = $record->disbursement_voucher->travel_order?->itineraries()->whereIsActual(true)->first();
                        if (!$actual_itinerary) {
                            DB::rollBack();
                            Notification::make()->title('Actual itinerary not found.')->warning()->send();

                            return false;
                        } else {
                            $actual_itinerary->update(['approved_at' => now()]);
                        }
                    }
                    $approvedAt = now();
                    $oicId = $this->tableFilters['as']['value'] ?? null;
                    if ($record->current_step_id >= ($record->previous_step_id ?? 0)) {
                        $record->update([
                            'signatory_date' => now(),
                            'current_step_id' => $record->current_step->next_step->id,
                        ]);
                    } else {
                        $record->update([
                            'signatory_date' => now(),
                            'current_step_id' => $record->previous_step_id,
                        ]);
                    }
                    $record->recordSignatoryApproval(auth()->id(), $approvedAt);
                    $record->refresh();
                    $record->activity_logs()->create([
                        'description' => $record->current_step->process.' '.$record->current_step->recipient.' by OIC: '.auth()->user()->employee_information->full_name.' for '.(EmployeeInformation::firstWhere('user_id', $oicId)?->full_name ?? 'signatory'),
                        'remarks' => $data['remarks'] ?? null,
                    ]);
                    DB::commit();
                    Notification::make()->title('Document Forwarded')->success()->send();
                })
                    ->form(function () {
                        return [
                            Placeholder::make('confirmation')
                                ->label('Important!')
                                ->content(new HtmlString("By forwarding this transaction as OIC, you are concurring in the contents of the Liquidation Report <br/>(including its supporting documents) on the signatory's behalf.")),
                            RichEditor::make('remarks')
                                ->label('Remarks (Optional)')
                                ->fileAttachmentsDisk('remarks'),
                        ];
                    })
                    ->modalWidth('4xl')
                    ->visible(fn($record) => filled($record) && $record->current_step_id == 4000 && !$record->for_cancellation)
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
            return view('livewire.oic.oic-signatory-liquidation-reports');
        }
    }
