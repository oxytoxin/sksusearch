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
use App\Models\FundClusterWFP;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;

class SelectWfpType extends Component implements HasTable
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
    public $fund_cluster;
    public $data = [];

    public function mount()
    {
        $isOfficeHead = auth()->user()->employee_information->office?->head_employee?->id == auth()->user()->employee_information->id;
        $this->fund_cluster = 1;

        $this->wfp_type = WpfType::all()->count();
        $head_id = WpfPersonnel::where('user_id', Auth::user()->id)->first()?->head_id;
        if($head_id === null)
        {
            $this->user_wfp_id = Auth::user()->employee_information->office->cost_centers->first()->fundAllocations->first()?->wpf_type_id;
            $this->wfp = WpfType::find($this->user_wfp_id);
            $this->cost_center_id = Auth::user()->employee_information->office->cost_centers->first()->id;
            $this->cost_centers = Auth::user()->employee_information->office->cost_centers;
            $this->office_id = Auth::user()->employee_information->office->id;
        }else{
            if($head_id === Auth::user()->id)
            {
                $this->user_wfp_id = Auth::user()->employee_information->office->cost_centers->first()->fundAllocations->first()?->wpf_type_id;
                $this->wfp = WpfType::find($this->user_wfp_id);
                $this->cost_center_id = Auth::user()->employee_information->office->cost_centers->first()->id;
                $this->cost_centers = Auth::user()->employee_information->office->cost_centers;
                $this->office_id = Auth::user()->employee_information->office->id;
            }else{
                $this->user_wfp_id = User::where('id', $head_id)->first()->employee_information->office->cost_centers->first()->fundAllocations->first()?->wpf_type_id;
                $this->wfp = WpfType::find($this->user_wfp_id);
                $this->cost_center_id = User::where('id', $head_id)->first()->employee_information->office->cost_centers->first()->id;
                $this->cost_centers = User::where('id', $head_id)->first()->employee_information->office->cost_centers;
                $this->office_id = User::where('id', $head_id)->first()->employee_information->office->id;
            }
        }

        $this->types = FundClusterWFP::whereHas('costCenters', function($query) {
            $query->where('office_id', $this->office_id)->whereHas('fundAllocations', function($query) {
                $query->where('wpf_type_id',  $this->user_wfp_id);
            });
        })->get();

    }

    protected function getTableQuery()
    {
        $user = WpfPersonnel::where('user_id', Auth::user()->id)->first();
       
        return CostCenter::query()->whereHas('fundAllocations', function ($query) {
            $query->where('is_locked', 1);
        })
        ->where('fund_cluster_w_f_p_s_id', $this->fund_cluster)
        ->whereIn('id', $this->cost_centers->pluck('id')->toArray())
        ->orWhereHas('wpfPersonnel', function ($query) {
            $query->where('user_id', Auth::user()->id)
            ->orWhere('head_id', Auth::user()->id)
            ->whereHas('cost_center', function ($subQuery) {
                $subQuery->where('fund_cluster_w_f_p_s_id', $this->fund_cluster);
            });
        });
        // if($user === null)
        // {
        //     return CostCenter::query()->whereHas('fundAllocations', function ($query) {
        //         $query->where('is_locked', 1);
        //     })
        //     ->where('fund_cluster_w_f_p_s_id', $this->fund_cluster)
        //     ->whereIn('id', $this->cost_centers->pluck('id')->toArray());
        // }else{
        //     return CostCenter::query()
        //     ->whereHas('fundAllocations', function ($query) {
        //         $query->where('is_locked', 1);
        //     })
        //     ->where('fund_cluster_w_f_p_s_id', $this->fund_cluster)
        //     ->orWhereHas('wpfPersonnel', function ($query) {
        //         $query->where('user_id', Auth::user()->id)
        //               ->orWhere('head_id', Auth::user()->id)
        //               ->whereHas('cost_center', function ($subQuery) {
        //                   $subQuery->where('fund_cluster_w_f_p_s_id', $this->fund_cluster);
        //               });
        //     });
        //     // ->whereIn('id', $this->cost_centers->pluck('id')->toArray());
        // }
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
                return $record->fundAllocations->where('wpf_type_id', $this->data['wfp_type'])->first()->wpfType->description;
            })
            ->label('WFP Type'),
            Tables\Columns\TextColumn::make('fundAllocations.amount')
            ->label('Amount')
            ->formatStateUsing(function ($record) {
                if($record->fundClusterWFP->id === 1 || $record->fundClusterWFP->id === 3) {
                    $sum = $record->fundAllocations->where('cost_center_id', $record->id)->where('wpf_type_id', $this->data['wfp_type'])->sum('initial_amount');
                    return '₱ '.number_format($sum, 2);
                }else
                {
                    return '₱ '.number_format($record->fundAllocations->where('cost_center_id', $record->id)->where('wpf_type_id', $this->data['wfp_type'])->sum('initial_amount'), 2);

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
            ->url(fn ($record): string => route('wfp.create-wfp', ['record' => $record, 'wfpType' => $this->data['wfp_type'], 'isEdit' => 0]))
            ->visible(fn ($record) => !$record->fundAllocations->where('wpf_type_id', $this->data['wfp_type'])->first()->fundDrafts()->exists()),
            Action::make('continue_draft')
            ->label('Continue Draft')
            ->color('warning')
            ->button()
            ->icon('heroicon-o-pencil')
            ->url(fn ($record): string => route('wfp.create-wfp', ['record' => $record, 'wfpType' => $this->data['wfp_type'], 'isEdit' => 0]))
            ->visible(fn ($record) => $record->fundAllocations->where('wpf_type_id', $this->data['wfp_type'])->first()->fundDrafts()->exists()),
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
                return $query->whereDoesntHave('wfp', function($query) use ($data) {
                    $query->where('wpf_type_id', $data['wfp_type']);
                })->whereHas('fundAllocations', function($query) use ($data) {
                    $query->where('wpf_type_id', $data['wfp_type']);
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
        return view('livewire.w-f-p.select-wfp-type');
    }
}
