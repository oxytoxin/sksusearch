<?php

    namespace App\Http\Livewire\WFP;

    use Filament\Forms;
    use App\Models\User;
    use Filament\Tables;
    use App\Models\Office;
    use App\Models\WpfType;
    use Livewire\Component;
    use App\Models\CostCenter;
    use App\Models\WpfPersonnel;
    use App\Models\FundAllocation;
    use App\Models\FundCluster;
    use App\Models\Wfp;
    use DB;
    use Filament\Tables\Actions\Action;
    use Filament\Tables\Filters\Filter;
    use Filament\Tables\Filters\Layout;
    use Illuminate\Support\Facades\Auth;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Tables\Filters\SelectFilter;
    use Illuminate\Database\Eloquent\Builder;
    use Filament\Tables\Concerns\InteractsWithTable;

    class SelectWfpTypeQ1 extends Component implements HasTable
    {
        use InteractsWithTable;

        public $types;
        public $user_wfp_id;
        public $head_id;
        public $wfp;
        public $cost_centers;
        public $cost_center_id;
        public $office_id;
        public $total_amount;
        public $wfp_type;
        public $wfp_types;

        public $fund_cluster;
        public $data = [
            'wfp_type' => 1
        ];
        protected $programmed = [];

        public $supplementalQuarterId = null;

        protected $queryString = ['supplementalQuarterId'];

        public function mount()
        {
            $this->fund_cluster = 1;
            $this->wfp_types = WpfType::get();
            $this->wfp_type = count($this->wfp_types);
            $has_personnel = WpfPersonnel::where('head_id', Auth::user()->id)->first();

            $designatedCostCentersId = WpfPersonnel::where('user_id', Auth::user()->id)->orWhere('head_id',
                Auth::user()->id)->get()->pluck('cost_center_id')->toArray();
            if ($has_personnel) {
                $this->cost_centers = array_merge(
                    array_diff_key(array_column(Auth::user()->employee_information->office->cost_centers()->get()->toArray(),
                        'id'), array_flip($designatedCostCentersId)),
                    array_column(CostCenter::whereIn('id', $designatedCostCentersId)->get()->toArray(), 'id')
                );
            } else {
                $this->cost_centers = Auth::user()->employee_information->office->cost_centers()->get()->pluck('id')->toArray();
            }
        }

        protected function getTableQuery()
        {
            $query_test = CostCenter::query()->with([
                'fundAllocations' => function ($query) {
                    $query->with('fundDrafts')->where('is_supplemental', 0)->orWhere(function ($query) {
                        $query->where('supplemental_quarter_id', '!=', null)->where('supplemental_quarter_id', '<=',
                            $this->supplementalQuarterId);
                    });
                }, 'wfp' => function ($query) {
                    $query->where('wpf_type_id', $this->data['wfp_type'])->with('wfpDetails');
                }, 'fundClusterWFP', 'mfo', 'mfoFee', 'office'
            ])
                ->whereHas('fundAllocations', function ($query) {
                    $query->where('supplemental_quarter_id', $this->supplementalQuarterId);
                })
                ->whereDoesntHave('wfp', function ($query) {
                    $query->where('supplemental_quarter_id', $this->supplementalQuarterId);
                })
                ->where('fund_cluster_id', $this->fund_cluster)
                ->whereIn('id', $this->cost_centers);
            return $query_test;
        }

        protected function getTableColumns()
        {
            return [
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('office.name')
                    ->wrap()->label('Office')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('head')
                    ->getStateUsing(function ($record) {
                        return $record->office->head_employee?->full_name;
                    }),
                Tables\Columns\TextColumn::make('mfo.name')->label('MFO')
                    ->wrap()->searchable()->sortable(),
                Tables\Columns\TextColumn::make('fundClusterWFP.name')
                    ->label('Fund Cluster')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('mfoFee.name')
                    ->label('MFO Fee')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('fundAllocations.wpf_type_id')
                    ->getStateUsing(function ($record) {
                        return $this->wfp_types->find($this->data['wfp_type'])->description;
                    })
                    ->label('WFP Period'),
                Tables\Columns\TextColumn::make('fundAllocations.amount')
                    ->label('Amount')
                    ->formatStateUsing(function ($record) {
                        if (in_array($record->fundClusterWFP->id, [1, 3, 9])) {
                            $sum1 = $record->fundAllocations->where('cost_center_id', $record->id)->where('wpf_type_id',
                                $this->data['wfp_type'])->where('supplemental_quarter_id', '!=',
                                null)->where('supplemental_quarter_id', '<=',
                                $this->supplementalQuarterId)->sum('initial_amount');
                            $sum2 = $record->fundAllocations->where('cost_center_id', $record->id)->where('wpf_type_id',
                                $this->data['wfp_type'])->where('is_supplemental', 0)->sum('initial_amount');
                            $subtotal = $sum1 + $sum2;
                            $programmed = 0;
                            if (count($record->wfp) > 0) {
                                $workFinancialPlans = $record->wfp->filter(function ($wfp) {
                                    return $wfp->is_supplemental === 0 || ($wfp->supplemental_quarter_id <= $this->supplementalQuarterId && $wfp->supplemental_quarter_id !== null);
                                });
                                if ($workFinancialPlans) {
                                    foreach ($workFinancialPlans as $wfp) {
                                        foreach ($wfp->wfpDetails as $allocation) {
                                            $programmed += ($allocation->total_quantity * $allocation->cost_per_unit);
                                        }
                                    }
                                }
                            }
                            return '₱ '.number_format($subtotal - $programmed, 2);
                        } else {
                            $sum1 = $record->fundAllocations->where('cost_center_id', $record->id)->where('wpf_type_id',
                                $this->data['wfp_type'])->where('supplemental_quarter_id', '!=',
                                null)->where('supplemental_quarter_id', '<=',
                                $this->supplementalQuarterId)->sum('initial_amount');
                            $sum2 = $record->fundAllocations->where('cost_center_id', $record->id)->where('wpf_type_id',
                                $this->data['wfp_type'])->where('supplemental_quarter_id',
                                null)->where('is_supplemental', 0)->sum('initial_amount');
                            if (count($record->wfp) > 0) {
                                $wfp = $record->wfp->filter(function ($wfp) {
                                    return $wfp->is_supplemental === 0 || $wfp->supplemental_quarter_id < $this->supplementalQuarterId;
                                });
                                $programmed = 0;
                                if (count($wfp) > 0) {
                                    $balance = $record->fundAllocations->sum('initial_amount');
                                    return '₱ '.number_format($balance, 2);
                                } else {
                                    if ($record->wfp->where('wpf_type_id',
                                            $this->data['wfp_type'])->where('cost_center_id',
                                            $record->id)->where('is_supplemental', 0)->first()->is_approved === 0) {
                                        $balance = $record->fundAllocations->where('is_supplemental',
                                            1)->sum('initial_amount');
                                        return '₱ '.number_format($balance, 2);
                                    } else {
                                        $wfpId = Wfp::find($record->wfp->where('wpf_type_id',
                                            $this->data['wfp_type'])->where('cost_center_id',
                                            $record->id)->where('is_supplemental', 0)->first()->id);
                                        $wfpDetails = $wfpId->wfpDetails()->get();

                                        foreach ($wfpDetails as $wfp) {
                                            $programmed += $wfp->total_quantity * $wfp->cost_per_unit;
                                        }
                                        $balance = $record->fundAllocations->sum('initial_amount') - $programmed;
                                        return '₱ '.number_format($balance, 2);
                                    }
                                }
                            } else {
                                $balance = $record->fundAllocations->where('is_supplemental', 1)->sum('initial_amount');
                                return '₱ '.number_format($balance, 2);
                            }
                        }
                    }),
            ];
        }

        protected function getTableActions(): array
        {
            return [
                Action::make('create_wfp')
                    ->label('Create WFP')
                    ->button()
                    ->icon('heroicon-o-plus')
                    ->url(fn($record): string => route('wfp.create-wfp', [
                        'record' => $record, 'wfpType' => $this->data['wfp_type'], 'isEdit' => 0, 'isSupplemental' => 1,
                        'supplementalQuarterId' => $this->supplementalQuarterId
                    ]))
                    ->visible(fn($record) => !$record->fundAllocations->where('wpf_type_id',
                        $this->data['wfp_type'])->where('supplemental_quarter_id',
                        $this->supplementalQuarterId)->first()->fundDrafts()->exists()),
                Action::make('continue_draft')
                    ->label('Continue Draft')
                    ->color('warning')
                    ->button()
                    ->icon('heroicon-o-pencil')
                    ->url(fn($record): string => route('wfp.create-wfp', [
                        'record' => $record, 'wfpType' => $this->data['wfp_type'], 'isEdit' => 0, 'isSupplemental' => 1,
                        'supplementalQuarterId' => $this->supplementalQuarterId
                    ]))
                    ->visible(fn($record) => $record->fundAllocations->where('wpf_type_id',
                        $this->data['wfp_type'])->where('supplemental_quarter_id',
                        $this->supplementalQuarterId)->first()->fundDrafts()->exists()),
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
                            ->options(WpfType::all()->pluck('description', 'id')->toArray())->default(1)
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $this->data = $data;
                        return $query->whereHas('fundAllocations', function ($query) use ($data) {
                            $query->where('wpf_type_id', $data['wfp_type'])->where('is_supplemental', 1);
                        });
                    }),
                SelectFilter::make('mfo')
                    ->label('MFO')
                    ->relationship('mfo', 'name')
            ];
        }

        public function filter($id)
        {
            $this->fund_cluster = $id;
            session(['fund_cluster3' => $id]);
        }

        public function render()
        {
            return view('livewire.w-f-p.select-wfp-type-q1');
        }
    }
