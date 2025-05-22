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
use App\Models\Wfp;
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
    public $fund_cluster;
    public $data = [];
    protected $programmed = [];

    public function mount()
    {
        $isOfficeHead = auth()->user()->employee_information->office?->head_employee?->id == auth()->user()->employee_information->id;
        $this->fund_cluster = 1;

        $this->wfp_type = WpfType::all()->count();
        $head_id = WpfPersonnel::where('user_id', Auth::user()->id)->first()?->head_id;
        $has_personnel = WpfPersonnel::where('user_id', Auth::user()->id)->orWhere('head_id', Auth::user()->id)->first();
        if($has_personnel){
            $this->cost_centers = Auth::user()->employee_information->office->cost_centers()
            ->with('wpfPersonnel', function ($query) {
                $query->where('user_id', Auth::user()->id)
                ->orWhere('head_id', Auth::user()->id);
            })->get();
        }else{
            $this->cost_centers = Auth::user()->employee_information->office->cost_centers;
        }


        $this->types = FundClusterWFP::whereHas('costCenters', function($query) {
            $query->where('office_id', $this->office_id)->whereHas('fundAllocations', function($query) {
                $query->where('wpf_type_id',  $this->user_wfp_id);
            });
        })->get();

        $test = CostCenter::whereHas('fundAllocations', function ($query) {
            $query->where('is_supplemental', 1);
        })
        ->whereIn('id', $this->cost_centers->pluck('id')->toArray())
        ->where('fund_cluster_w_f_p_s_id', $this->fund_cluster)->get();
        // dd($test->first()->fundAllocations()->where('is_supplemental', 1)->get());

    }

    protected function getTableQuery()
    {
        // $user = WpfPersonnel::where('user_id', Auth::user()->id)->first();
        $query_test = CostCenter::query()->whereHas('fundAllocations', function ($query) {
            $query->where('is_supplemental', 1);
        })
        ->whereIn('id', $this->cost_centers->pluck('id')->toArray())
        ->where('fund_cluster_w_f_p_s_id', $this->fund_cluster);
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
                return $record->fundAllocations->where('wpf_type_id', $this->data['wfp_type'])->where('is_supplemental', 1)->first()->wpfType->description;
            })
            ->label('WFP Period'),
            Tables\Columns\TextColumn::make('fundAllocations.amount')
            ->label('Amount')
            ->formatStateUsing(function ($record) {
                if($record->fundClusterWFP->id === 1 || $record->fundClusterWFP->id === 3) {

                    $sum = $record->fundAllocations->where('cost_center_id', $record->id)->where('wpf_type_id', $this->data['wfp_type'])->where('is_supplemental', 1)->sum('initial_amount');
                    return '₱ '.number_format($sum, 2);
                }else
                {
                    $allocated = $record->fundAllocations->where('is_supplemental', 0)->sum('initial_amount');
                    $fund_allocation = $record->fundAllocations->where('cost_center_id', $record->id)->where('wpf_type_id', $this->data['wfp_type'])->where('is_supplemental', 0)->first();
                    //$allocated = $fund_allocation->initial_amount;

                    if ($record->wfp !== null)
                    {
                        $wfp = $record->wfp->where('wpf_type_id', $this->data['wfp_type'])->where('is_supplemental', 0)->get();
                        $programmed = 0;
                        if($record->wfp->where('wpf_type_id', $this->data['wfp_type'])->where('cost_center_id', $record->id)->where('is_supplemental', 0)->first() === null)
                        {
                            $balance = $record->fundAllocations->sum('initial_amount');
                             return '₱ ' . number_format($balance, 2);
                        }else{
                            if($record->wfp->where('wpf_type_id', $this->data['wfp_type'])->where('cost_center_id', $record->id)->where('is_supplemental', 0)->first()->is_approved === 0)
                            {
                                  $balance = $record->fundAllocations->where('is_supplemental', 1)->sum('initial_amount');
                                  return '₱ ' . number_format($balance, 2);
                            }else{
                                $wfpId = Wfp::find($record->wfp->where('wpf_type_id', $this->data['wfp_type'])->where('cost_center_id', $record->id)->where('is_supplemental', 0)->first()->id);
                                $wfpDetails = $wfpId->wfpDetails()->get();

                                foreach($wfpDetails as $wfp)
                                {
                                    $programmed += $wfp->total_quantity * $wfp->cost_per_unit;

                                }
                                $balance = $record->fundAllocations->sum('initial_amount') - $programmed;
                                return '₱ ' . number_format($balance, 2);
                            }

                        }


                    }else{
                        $balance = $record->fundAllocations->where('is_supplemental', 1)->sum('initial_amount');
                        return '₱ ' . number_format($balance, 2);
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
            ->url(fn ($record): string => route('wfp.create-wfp', ['record' => $record, 'wfpType' => $this->data['wfp_type'], 'isEdit' => 0, 'isSupplemental' => 1]))
            ->visible(fn ($record) => !$record->fundAllocations->where('wpf_type_id', $this->data['wfp_type'])->where('is_supplemental', 1)->first()->fundDrafts()->exists()),
            Action::make('continue_draft')
            ->label('Continue Draft')
            ->color('warning')
            ->button()
            ->icon('heroicon-o-pencil')
            ->url(fn ($record): string => route('wfp.create-wfp', ['record' => $record, 'wfpType' => $this->data['wfp_type'], 'isEdit' => 0, 'isSupplemental' => 1]))
            ->visible(fn ($record) => $record->fundAllocations->where('wpf_type_id', $this->data['wfp_type'])->where('is_supplemental', 1)->first()->fundDrafts()->exists()),
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
                return $query->whereHas('fundAllocations', function($query) use ($data) {
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
