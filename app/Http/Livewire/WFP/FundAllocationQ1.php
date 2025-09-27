<?php

    namespace App\Http\Livewire\WFP;

    use App\Models\MFO;
    use Filament\Forms;
    use Filament\Tables;
    use App\Models\MfoFee;
    use App\Models\WpfType;
    use Livewire\Component;
    use App\Models\CostCenter;
    use App\Models\CategoryGroup;
    use App\Models\FundCluster;
    use Filament\Tables\Actions\Action;
    use Filament\Tables\Filters\Filter;
    use Filament\Tables\Filters\Layout;
    use Filament\Forms\Components\Select;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Forms\Components\TextInput;
    use Filament\Notifications\Notification;
    use Filament\Tables\Actions\ActionGroup;
    use Filament\Tables\Filters\SelectFilter;
    use Illuminate\Database\Eloquent\Builder;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Tables\Actions\ViewAction;
    use Filament\Tables\Columns\ViewColumn;

    class FundAllocationQ1 extends Component implements HasTable
    {
        use InteractsWithTable;

        public $wfp_type;
        public $fund_cluster;
        public $group_keys = [];
        public $isPresident;
        public $data = [];

        public $supplementQuarterId = null;

        protected $queryString = ['supplementQuarterId'];

        public function mount()
        {
            $this->isPresident = auth()->user()->employee_information->office_id == 51 && auth()->user()->employee_information->position_id == 34;
            if (session()->has('fund_cluster')) {
                $this->fund_cluster = session('fund_cluster');
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
            $group = CategoryGroup::all()->pluck('name', 'id');

            foreach ($group as $key => $value) {
                $this->group_keys[$value] = 0;
            }
        }

        protected function getTableQuery()
        {
            return CostCenter::query()->where('fund_cluster_w_f_p_s_id', $this->fund_cluster);
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
                    ->label('MFO Fee')->searchable()->sortable()->wrap(),
                Tables\Columns\TextColumn::make('fundAllocations.wpf_type_id')
                    ->getStateUsing(function ($record) {
                        return $record->fundAllocations->where('wpf_type_id',
                            $this->data['wfp_type'])->first()?->wpfType->description;
                        // return $record->fundAllocations->first()?->wpfType->description;
                    })
                    ->label('WFP Period'),
                Tables\Columns\TextColumn::make('fundAllocations.amount')
                    ->label('Amount')
                    ->formatStateUsing(function ($record) {
                        if ($record->fundClusterWFP->id === 1 || $record->fundClusterWFP->id === 3) {
                            $sum = $record->fundAllocations->where('cost_center_id', $record->id)->where('wpf_type_id',
                                $this->data['wfp_type'])->sum('initial_amount');
                            return '₱ '.number_format($sum, 2);
                        } else {
                            return '₱ '.number_format($record->fundAllocations->where('cost_center_id',
                                    $record->id)->where('wpf_type_id', $this->data['wfp_type'])->sum('initial_amount'),
                                    2);

                        }
                    }),
                ViewColumn::make('status')
                    ->label('WFP Status')->view('tables.columns.wfp-status'),

            ];
        }

        protected function getTableActions(): array
        {
            return [
                Action::make('allocate_fund')
                    ->icon('ri-money-dollar-circle-line')
                    ->label('Allocate Fund')
                    ->button()
                    ->url(function ($record) {
                        return route('wfp.allocate-funds',
                            ['record' => $record, 'supplementalQuarterId' => $this->supplementalQuarterId]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn($record) => !$record->fundAllocations()->where('wpf_type_id',
                            $this->data['wfp_type'])->exists() && !$this->isPresident),
                Action::make('edit_fund')
                    ->icon('ri-pencil-line')
                    ->label('Edit fund')
                    ->button()
                    ->color('warning')
                    ->url(fn(CostCenter $record): string => route('wfp.edit-allocate-funds',
                        ['record' => $record, 'wfpType' => $this->data['wfp_type']]))
                    ->visible(fn($record) => $record->fundAllocations()->where('wpf_type_id',
                            $this->data['wfp_type'])->exists() && !$record->fundAllocations()->where('wpf_type_id',
                            $this->data['wfp_type'])->where('is_locked', true)->exists() && !$this->isPresident),
                Action::make('lock_fund')
                    ->icon('ri-lock-line')
                    ->label('Lock fund')
                    ->button()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->fundAllocations()->update([
                            'is_locked' => true
                        ]);
                        Notification::make()->title('Operation Success')->body('Fund Successfully Locked')->success()->send();
                    })
                    ->visible(fn($record) => $record->fundAllocations()->where('wpf_type_id',
                            $this->data['wfp_type'])->exists() && !$record->fundAllocations()->where('wpf_type_id',
                            $this->data['wfp_type'])->where('is_locked', true)->exists() && !$this->isPresident),
                ActionGroup::make([
                    Action::make('unlock_fund')
                        ->icon('ri-lock-line')
                        ->label('Unlock fund')
                        ->button()
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->fundAllocations()->update([
                                'is_locked' => false
                            ]);

                            Notification::make()->title('Operation Success')->body('Fund Successfully Unlocked')->success()->send();

                        })
                        ->visible(fn($record) => $record->fundAllocations()->where('wpf_type_id',
                                $this->data['wfp_type'])->exists() && !$record->fundAllocations()->where('wpf_type_id',
                                $this->data['wfp_type'])->where('is_locked',
                                false)->exists() && !$this->isPresident || $record->fundAllocations()->first() == false),
                    ViewAction::make('view_allocation')
                        ->label('View Allocation')
                        ->button()
                        ->color('warning')
                        ->icon('ri-eye-line')
                        ->url(fn(CostCenter $record): string => route('wfp.view-allocated-funds',
                            ['record' => $record, 'wfpType' => $this->data['wfp_type']]))
                        ->visible(fn($record) => $record->fundAllocations()->where('wpf_type_id',
                                $this->data['wfp_type'])->exists() && !$record->fundAllocations()->where('wpf_type_id',
                                $this->data['wfp_type'])->where('is_locked', false)->exists() && !$this->isPresident),
                    //action for adding supplemental fund
                    Action::make('supplemental_fund')
                        ->icon('ri-money-dollar-circle-line')
                        ->label('Add Supplemental Fund')
                        ->button()
                        ->color('success')
                        ->url(fn(CostCenter $record): string => route('wfp.add-supplemental-fund',
                            ['record' => $record, 'wfpType' => $this->data['wfp_type']]))
                        ->visible(fn(CostCenter $record) => $record->wfp()->where('supplemental_quarter_id',
                                $this->supplementQuarterId)->first()?->is_approved === 1 && !$record->hasSupplementalFund()),
                    Action::make('view_supplemental')
                        ->icon('ri-eye-line')
                        ->label('View Supplemental Fund')
                        ->button()
                        ->color('success')
                        ->url(fn(CostCenter $record): string => route('wfp.view-supplemental-fund',
                            ['record' => $record, 'wfpType' => $this->data['wfp_type']]))
                        ->visible(fn(CostCenter $record) => $record->wfp()->where('supplemental_quarter_id',
                                $this->supplementQuarterId)->first()?->is_approved === 1 && $record->hasSupplementalFund()),
                ]),

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
                            ->options(WpfType::all()->pluck('description', 'id')->toArray())->default(1)
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $this->data = $data;
                        return $query->with('fundAllocations', function ($query) use ($data) {
                            $query->where('wpf_type_id', $data['wfp_type']);
                        });
                    }),
                Filter::make('wfp_status')
                    ->form([
                        Forms\Components\Select::make('wfp_status')
                            ->label('WFP Status')
                            ->options([
                                20 => 'All',
                                1 => 'Approved',
                                0 => 'Pending',
                                500 => 'Modification Request'
                            ])->default(20)
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['wfp_status'] != 20) {
                            return $query->whereHas('wfp', function ($query) use ($data) {
                                $query->where('wpf_type_id', $this->data['wfp_type'])->where('is_approved',
                                    $data['wfp_status']);
                            });
                        }
                        return $query;
                    }),
                // Filter::make('mfo_id')
                // ->form([
                //     Forms\Components\Select::make('mfo')
                //     ->options(MFO::all()->pluck('name', 'id')->prepend('All', ''))
                //     ->reactive(),
                // ])
                // ->query(function (Builder $query, array $data): Builder {
                //     if (!empty($data['mfo_id'])) {
                //         return $query->where('m_f_o_s_id', $data['mfo']);
                //     }
                //     return $query;
                // }),
                // Filter::make('mfo_fee_id')
                // ->form([
                //     Forms\Components\Select::make('mfoFee')
                //     ->options(MFO::all()->pluck('name', 'id')->prepend('All', '')),
                // ])
                // ->query(function (Builder $query, array $data): Builder {
                //     if (!empty($data['mfo_fee_id'])) {
                //         return $query->where('m_f_o_fee_id', $data['mfo_fee_id']);
                //     }
                //     return $query;
                // }),
            ];
        }

        public function filter($id)
        {
            $this->fund_cluster = $id;
            session(['fund_cluster' => $id]);
        }

        public function render()
        {
            return view('livewire.w-f-p.fund-allocation-q1');
        }
    }
