<?php

    namespace App\Http\Livewire\WFP;

    use App\Models\MFO;
    use Livewire\Component;
    use Carbon\Carbon;
    use App\Models\WpfType;
    use Filament\Tables\Actions\Action;
    use Filament\Forms;
    use App\Models\Wfp;
    use App\Models\WfpApprovalRemark;
    use Filament\Tables;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Tables\Filters\Layout;
    use Filament\Tables\Filters\SelectFilter;
    use Illuminate\Database\Eloquent\Builder;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Tables\Filters\Filter;
    use App\Jobs\SendSmsJob;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Log;

    class WfpSubmissions extends Component implements HasTable
    {
        use InteractsWithTable;

        public $wfp_type;
        public $fund_cluster;
        public $isPresident;

        public $supplementalQuarterId = null;
        protected $queryString = ['supplementalQuarterId'];

        public  $is164 = null;

        public function mount($filter)
        {
            $this->isPresident = auth()->user()->employee_information->office_id == 51 && auth()->user()->employee_information->position_id == 34;
            if (session()->has('fund_cluster2')) {
                $this->fund_cluster = session('fund_cluster2');
            } else {
                $this->fund_cluster = 1;
            }
            // if($filter)
            // {
            //     $this->filter($filter);
            // }else{

            //     $this->fund_cluster = 1;
            // }
            $this->wfp_type = WpfType::all()->count();
        }

        protected function getTableQuery()
        {
            return Wfp::query()->where('fund_cluster_id', $this->fund_cluster)->where('is_supplemental', 0);
        }

        protected function getTableColumns()
        {
            return [
                Tables\Columns\TextColumn::make('id')->label('ID')->toggleable(isToggledHiddenByDefault: true)->searchable(),
                Tables\Columns\TextColumn::make('wfpType.description')->label('WFP Period')->searchable(),
                Tables\Columns\TextColumn::make('costCenter.name')->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('costCenter.office.name')->label('Office')->searchable(),
                Tables\Columns\TextColumn::make('fundClusterWfp.name')->label('Fund Cluster')->searchable(),
                Tables\Columns\TextColumn::make('costCenter.mfo.name')->label('MFO')->searchable(),
                Tables\Columns\TextColumn::make('fund_description')->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Date Created')
                    ->formatStateUsing(fn($record) => Carbon::parse($record->updated_at)->format('F d, Y h:i A'))
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.employee_information.full_name')
                    ->label('Created By')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('is_approved')
                    ->label('Status')
                    ->formatStateUsing(function ($record) {
                        if ($record->is_approved === 0) {
                            return 'Pending';
                        } elseif ($record->is_approved === 1) {
                            return 'Approved';
                        } elseif ($record->is_approved === 500) {
                            return 'For Modification';
                        }
                    })
            ];
        }

        public function getTableActions()
        {
            return [
                Tables\Actions\ActionGroup::make([
                    Action::make('view wfp')
                        ->label('View WFP')
                        ->button()
                        ->icon('heroicon-o-eye')
                        ->url(fn($record): string => route('wfp.print-wfp',[
                            'record' => $record,
                            'isSupplemental' => 0,
                            'wfpType' => $record->wpf_type_id,
                            'is164' => !in_array($record->fund_cluster_id, [1, 3,9]),
                        ])),
                    Action::make('view ppmp')
                        ->label('View PPMP')
                        ->button()
                        ->icon('heroicon-o-eye')
                        ->url(fn($record): string => route('wfp.print-ppmp', [
                            'record' => $record, 'isSupplemental' => 0, 'costCenterId' => $record->cost_center_id,
                            'wfpType' => $record->wpf_type_id
                        ])),
                    Action::make('view pre')
                        ->label('View PRE')
                        ->button()
                        ->icon('heroicon-o-eye')
                        ->url(fn($record): string => route('wfp.print-pre', [
                            'record' => $record, 'isSupplemental' => 0, 'costCenterId' => $record->cost_center_id,
                            'wfpType' => $record->wpf_type_id
                        ]))
                ]),
                Action::make('approve')
                    ->label('Approve WFP')
                    ->color('warning')
                    ->button()
                    ->icon('heroicon-o-check-circle')
                    ->action(function ($record) {
                        $record->update(['is_approved' => 1]);

                        // ========== SMS NOTIFICATION START ==========
                        // COMMENTED OUT - TO BE CONFIRMED BY ACCOUNTANT
                        // try {
                        //     // Validate WFP record exists
                        //     if (!$record) {
                        //         Log::warning('SMS notification skipped: WFP record not found', [
                        //             'context' => 'WFP_APPROVAL'
                        //         ]);
                        //     } else {
                        //         // Check if cost center relationship exists
                        //         if (!$record->costCenter) {
                        //             Log::warning('SMS notification skipped: Cost center not found for WFP', [
                        //                 'wfp_id' => $record->id,
                        //                 'context' => 'WFP_APPROVAL'
                        //             ]);
                        //         } else {
                        //             $costCenter = $record->costCenter;
                        //
                        //             // Check if office relationship exists
                        //             if (!$costCenter->office) {
                        //                 Log::warning('SMS notification skipped: Office not found for cost center', [
                        //                     'cost_center_id' => $costCenter->id,
                        //                     'cost_center_name' => $costCenter->name,
                        //                     'wfp_id' => $record->id,
                        //                     'context' => 'WFP_APPROVAL'
                        //                 ]);
                        //             } else {
                        //                 $office = $costCenter->office;
                        //
                        //                 // Check if head employee exists
                        //                 if (!$office->head_employee) {
                        //                     Log::warning('SMS notification skipped: Head employee not found for office', [
                        //                         'office_id' => $office->id,
                        //                         'office_name' => $office->office_name ?? 'N/A',
                        //                         'cost_center_id' => $costCenter->id,
                        //                         'wfp_id' => $record->id,
                        //                         'context' => 'WFP_APPROVAL'
                        //                     ]);
                        //                 } else {
                        //                     $headEmployee = $office->head_employee;
                        //
                        //                     // Check if user relationship exists
                        //                     if (!$headEmployee->user) {
                        //                         Log::warning('SMS notification skipped: User not found for head employee', [
                        //                             'employee_id' => $headEmployee->id,
                        //                             'employee_name' => $headEmployee->first_name . ' ' . $headEmployee->last_name,
                        //                             'office_id' => $office->id,
                        //                             'wfp_id' => $record->id,
                        //                             'context' => 'WFP_APPROVAL'
                        //                         ]);
                        //                     } else {
                        //                         $user = $headEmployee->user;
                        //
                        //                         // Get phone number with null safety
                        //                         $phone = $headEmployee->contact_number ?? null;
                        //                         // $phone = "09366303145"; // TEST PHONE - Uncomment for testing
                        //
                        //                         if (!$phone) {
                        //                             Log::warning('SMS notification skipped: No contact number for head employee', [
                        //                                 'user_id' => $user->id,
                        //                                 'user_name' => $user->name,
                        //                                 'employee_id' => $headEmployee->id,
                        //                                 'wfp_id' => $record->id,
                        //                                 'context' => 'WFP_APPROVAL'
                        //                             ]);
                        //                         } else {
                        //                             // Prepare data with null safety
                        //                             $programmedAmount = $record->program_allocated ?? 0;
                        //                             $totalAllocation = $record->total_allocated_fund ?? 0;
                        //                             $programmedFormatted = $programmedAmount > 0 ? number_format($programmedAmount, 2) : '0.00';
                        //                             $totalFormatted = $totalAllocation > 0 ? number_format($totalAllocation, 2) : '0.00';
                        //
                        //                             $fundName = $record->fundClusterWfp->name ?? 'N/A';
                        //                             $mfoName = $costCenter->mfo->name ?? 'N/A';
                        //                             $costCenterName = $costCenter->name ?? 'N/A';
                        //
                        //                             // Get WFP period name
                        //                             $wfpPeriod = $record->wfpType ? $record->wfpType->name : 'N/A';
                        //
                        //                             // Build SMS message
                        //                             $message = "Your expenditure programming under Fund {$fundName} {$mfoName} {$costCenterName} for the financial period {$wfpPeriod} has been approved. You programmed ₱{$programmedFormatted} out of your total allocation of ₱{$totalFormatted}.";
                        //
                        //                             // Dispatch SMS job
                        //                             SendSmsJob::dispatch(
                        //                                 $phone,
                        //                                 $message,
                        //                                 'WFP_APPROVAL',
                        //                                 $user->id,
                        //                                 Auth::id()
                        //                             );
                        //
                        //                             Log::info('WFP approval SMS queued successfully', [
                        //                                 'phone' => $phone,
                        //                                 'user_id' => $user->id,
                        //                                 'user_name' => $user->name,
                        //                                 'wfp_id' => $record->id,
                        //                                 'cost_center_id' => $costCenter->id,
                        //                                 'cost_center_name' => $costCenterName,
                        //                                 'programmed_amount' => $programmedFormatted,
                        //                                 'total_allocation' => $totalFormatted,
                        //                                 'wfp_period' => $wfpPeriod,
                        //                                 'context' => 'WFP_APPROVAL'
                        //                             ]);
                        //                         }
                        //                     }
                        //                 }
                        //             }
                        //         }
                        //     }
                        // } catch (\Exception $e) {
                        //     Log::error('WFP approval SMS notification failed', [
                        //         'error' => $e->getMessage(),
                        //         'line' => $e->getLine(),
                        //         'file' => $e->getFile(),
                        //         'wfp_id' => $record->id ?? null,
                        //         'context' => 'WFP_APPROVAL'
                        //     ]);
                        // }
                        // ========== SMS NOTIFICATION END ==========
                    })
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->is_approved === 0 && !$this->isPresident),
                Action::make('modify')
                    ->label('Request Modification')
                    ->color('danger')
                    ->button()
                    ->icon('heroicon-o-pencil-alt')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Reason for Modification')
                            ->required()

                    ])
                    ->action(function ($record, $data) {
                        WfpApprovalRemark::create([
                            'wfps_id' => $record->id,
                            'user_id' => auth()->user()->id,
                            'remarks' => $data['reason']
                        ]);
                        $record->update([
                            'is_approved' => 500
                        ]);

                        // ========== SMS NOTIFICATION START ==========
                        // COMMENTED OUT - TO BE CONFIRMED BY ACCOUNTANT
                        // try {
                        //     // Validate WFP record exists
                        //     if (!$record) {
                        //         Log::warning('SMS notification skipped: WFP record not found', [
                        //             'context' => 'WFP_MODIFICATION'
                        //         ]);
                        //     } else {
                        //         // Check if cost center relationship exists
                        //         if (!$record->costCenter) {
                        //             Log::warning('SMS notification skipped: Cost center not found for WFP', [
                        //                 'wfp_id' => $record->id,
                        //                 'context' => 'WFP_MODIFICATION'
                        //             ]);
                        //         } else {
                        //             $costCenter = $record->costCenter;
                        //
                        //             // Check if office relationship exists
                        //             if (!$costCenter->office) {
                        //                 Log::warning('SMS notification skipped: Office not found for cost center', [
                        //                     'cost_center_id' => $costCenter->id,
                        //                     'cost_center_name' => $costCenter->name,
                        //                     'wfp_id' => $record->id,
                        //                     'context' => 'WFP_MODIFICATION'
                        //                 ]);
                        //             } else {
                        //                 $office = $costCenter->office;
                        //
                        //                 // Check if head employee exists
                        //                 if (!$office->head_employee) {
                        //                     Log::warning('SMS notification skipped: Head employee not found for office', [
                        //                         'office_id' => $office->id,
                        //                         'office_name' => $office->office_name ?? 'N/A',
                        //                         'cost_center_id' => $costCenter->id,
                        //                         'wfp_id' => $record->id,
                        //                         'context' => 'WFP_MODIFICATION'
                        //                     ]);
                        //                 } else {
                        //                     $headEmployee = $office->head_employee;
                        //
                        //                     // Check if user relationship exists
                        //                     if (!$headEmployee->user) {
                        //                         Log::warning('SMS notification skipped: User not found for head employee', [
                        //                             'employee_id' => $headEmployee->id,
                        //                             'employee_name' => $headEmployee->first_name . ' ' . $headEmployee->last_name,
                        //                             'office_id' => $office->id,
                        //                             'wfp_id' => $record->id,
                        //                             'context' => 'WFP_MODIFICATION'
                        //                         ]);
                        //                     } else {
                        //                         $user = $headEmployee->user;
                        //
                        //                         // Get phone number with null safety
                        //                         $phone = $headEmployee->contact_number ?? null;
                        //                         // $phone = "09366303145"; // TEST PHONE - Uncomment for testing
                        //
                        //                         if (!$phone) {
                        //                             Log::warning('SMS notification skipped: No contact number for head employee', [
                        //                                 'user_id' => $user->id,
                        //                                 'user_name' => $user->name,
                        //                                 'employee_id' => $headEmployee->id,
                        //                                 'wfp_id' => $record->id,
                        //                                 'context' => 'WFP_MODIFICATION'
                        //                             ]);
                        //                         } else {
                        //                             // Prepare data with null safety
                        //                             $fundName = $record->fundClusterWfp->name ?? 'N/A';
                        //                             $mfoName = $costCenter->mfo->name ?? 'N/A';
                        //                             $costCenterName = $costCenter->name ?? 'N/A';
                        //
                        //                             // Get WFP period name
                        //                             $wfpPeriod = $record->wfpType ? $record->wfpType->name : 'N/A';
                        //
                        //                             // Get remarks from form data
                        //                             $remarks = $data['reason'] ?? 'No remarks provided';
                        //
                        //                             // Build SMS message
                        //                             $message = "Your expenditure programming under Fund {$fundName} {$mfoName} {$costCenterName} for the financial period {$wfpPeriod} has been returned for modification with the following remarks: \"{$remarks}\". Please modify your budget accordingly.";
                        //
                        //                             // Dispatch SMS job
                        //                             SendSmsJob::dispatch(
                        //                                 $phone,
                        //                                 $message,
                        //                                 'WFP_MODIFICATION',
                        //                                 $user->id,
                        //                                 Auth::id()
                        //                             );
                        //
                        //                             Log::info('WFP modification request SMS queued successfully', [
                        //                                 'phone' => $phone,
                        //                                 'user_id' => $user->id,
                        //                                 'user_name' => $user->name,
                        //                                 'wfp_id' => $record->id,
                        //                                 'cost_center_id' => $costCenter->id,
                        //                                 'cost_center_name' => $costCenterName,
                        //                                 'wfp_period' => $wfpPeriod,
                        //                                 'remarks' => $remarks,
                        //                                 'context' => 'WFP_MODIFICATION'
                        //                             ]);
                        //                         }
                        //                     }
                        //                 }
                        //             }
                        //         }
                        //     }
                        // } catch (\Exception $e) {
                        //     Log::error('WFP modification request SMS notification failed', [
                        //         'error' => $e->getMessage(),
                        //         'line' => $e->getLine(),
                        //         'file' => $e->getFile(),
                        //         'wfp_id' => $record->id ?? null,
                        //         'context' => 'WFP_MODIFICATION'
                        //     ]);
                        // }
                        // ========== SMS NOTIFICATION END ==========

                        //delete drafts (FIX)
                        // $record->fundAllocation->fundDraft->draft_amounts()->delete();
                        // $record->fundAllocation->fundDraft->draft_items()->delete();
                        // $record->fundAllocation->fundDraft->delete();
                    })->requiresConfirmation()
                    ->visible(fn($record) => $record->is_approved === 0 && !$this->isPresident),
            ];
        }

        protected function getTableFiltersLayout(): ?string
        {
            return Layout::AboveContent;
        }

        protected function getTableFilters(): array
        {
            return [
                Filter::make('wfp_type')
                    ->form([
                        Forms\Components\Select::make('wfp_type')
                            ->label('WFP Period')
                            ->options(WpfType::all()->pluck('description', 'id')->prepend('All', ''))
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['wfp_type'])) {
                            return $query->where('wpf_type_id', $data['wfp_type']);
                        }
                        return $query; // Return the original query if "All" is selected
                    }),
                Filter::make('mfo')
                    ->form([
                        Forms\Components\Select::make('mfo')
                            ->options(MFO::all()->pluck('name', 'id')->prepend('All', ''))
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['mfo'])) {
                            // if(!in_array($data['mfo'],[1,3,9])){
                            //     $this->is164 = true;
                            // }
                            return $query->whereHas('costCenter', function ($query) use ($data) {
                                $query->where('m_f_o_s_id', $data['mfo']);
                            });
                        }
                        return $query; // Return the original query if "All" is selected
                    }),
                Filter::make('is_approved')
                    ->form([
                        Forms\Components\Select::make('is_approved')
                            ->label('Status')
                            ->options([
                                '' => 'All',
                                0 => 'Pending',
                                1 => 'Approved',
                                500 => 'For Modification'
                            ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['is_approved'])) {
                            return $query->where('is_approved', $data['is_approved']);
                        }
                        return $query; // Return the original query if "All" is selected
                    }),
            ];
        }

        public function filter($id)
        {
            $this->fund_cluster = $id;
            session(['fund_cluster2' => $id]);
        }

        public function render()
        {
            return view('livewire.w-f-p.wfp-submissions');
        }
    }
