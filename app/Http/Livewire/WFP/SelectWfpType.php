<?php

namespace App\Http\Livewire\WFP;

use App\Models\Office;
use App\Models\WpfType;
use Livewire\Component;
use App\Models\CostCenter;
use App\Models\FundAllocation;
use App\Models\FundClusterWFP;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;

class SelectWfpType extends Component implements HasTable
{
    use InteractsWithTable;

    public $types;
    public $user_wfp_id;
    public $wfp;
    public $cost_centers;
    public $cost_center_id;
    public $office_id;
    public $total_amount;
    public $wfp_type;
    public $fund_cluster;

    public function mount()
    {
        $this->fund_cluster = 1;
        $this->wfp_type = WpfType::all()->count();
        $this->user_wfp_id = Auth::user()->employee_information->office->cost_centers->first()->fundAllocations->first()?->wpf_type_id;
        $this->wfp = WpfType::find($this->user_wfp_id);
        $this->cost_center_id = Auth::user()->employee_information->office->cost_centers->first()->id;
        $this->cost_centers = Auth::user()->employee_information->office->cost_centers;
        $this->office_id = Auth::user()->employee_information->office->id;
        $this->types = FundClusterWFP::whereHas('costCenters', function($query) {
            $query->where('office_id', $this->office_id)->whereHas('fundAllocations', function($query) {
                $query->where('wpf_type_id',  $this->user_wfp_id);
            });
        })->get();

    }

    protected function getTableQuery()
    {
        return CostCenter::query()->whereHas('fundAllocations', function ($query) {
            $query->where('is_locked', 1);
        })->where('fund_cluster_w_f_p_s_id', $this->fund_cluster)->whereIn('id', $this->cost_centers->pluck('id')->toArray());
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
                return $record->fundAllocations->first()?->wpfType->description;
            })
            ->label('WFP Type'),
            Tables\Columns\TextColumn::make('fundAllocations.amount')
            ->label('Amount')
            ->formatStateUsing(function ($record) {
                if($record->fundClusterWFP->id === 1 || $record->fundClusterWFP->id === 3) {
                    $sum = $record->fundAllocations->where('cost_center_id', $record->id)->where('wpf_type_id', $record->fundAllocations->first()?->wpf_type_id)->sum('initial_amount');
                    return '₱ '.number_format($sum, 2);
                }else
                {
                    return '₱ '.number_format($record->fundAllocations->sum('initial_amount'), 2);

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
            ->url(fn ($record): string => route('wfp.create-wfp', $record))
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
    }


    public function render()
    {
        return view('livewire.w-f-p.select-wfp-type');
    }
}
