<?php

namespace App\Http\Livewire\WFP;

use App\Models\WpfType;
use Livewire\Component;
use App\Models\CostCenter;
use App\Models\CategoryGroup;
use App\Models\SupplementalQuarter;

class ViewSupplementalFunds extends Component
{
    public $record;
    public $category_groups;
    public $category_groups_supplemental;
    public $wfp_type;
    public $selectedType;
    public $fundInitialAmount;
    public $fund_description;
    public $allocations = [];
    public $amounts = [];
    public $programmed = [];
    public $balances = [];
    public $supplemental_quarter;


     //for 164
     public $supplemental_allocation;
     public $supplemental_allocation_description;
     public $balance_164;
     public $sub_total_164;

    public function mount($record, $wfpType)
    {
        $this->record = CostCenter::find($record);
        $this->category_groups = CategoryGroup::where('is_active', 1)->get();
        $this->category_groups_supplemental = CategoryGroup::whereHas('fundAllocations', function($query) {
            $query->where('cost_center_id', $this->record->id)->where('initial_amount', '>', 0);
        })->where('is_active', 1)->get();
        $this->wfp_type = WpfType::all();
        $this->selectedType = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->first()->wpf_type_id;
        $this->fundInitialAmount = $this->record->fundAllocations->where('wpf_type_id', $this->selectedType)->first()->initial_amount;
        $this->fund_description = $this->record->fundAllocations->first()->description;
        $this->supplemental_quarter = SupplementalQuarter::where('is_active', 1)->first();
       // $this->amounts = array_fill_keys($this->category_groups->pluck('id')->toArray(), 0);
        foreach($this->record->wfp->where('wpf_type_id', $this->selectedType)->where('cost_center_id', $this->record->id)->get() as $wfp)
        {
            foreach($wfp->wfpDetails as $allocation)
            {
            if (!isset($this->programmed[$allocation->category_group_id])) {
                $this->programmed[$allocation->category_group_id] = 0;
            }
            $this->programmed[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
            }
        }

        foreach ($this->record->fundAllocations->where('wpf_type_id', $wfpType) as $allocation) {
            $this->allocations[$allocation->category_group_id] = $allocation->initial_amount;
        }



        //i want to get the balances from the allocations subtracted by the programmed, use map
        $this->balances = collect($this->allocations)->map(function($allocation, $categoryGroupId) {
            return (float)$allocation - (float)$this->calculateSubTotal($categoryGroupId);
        });

        $this->balance_164 = $this->fundInitialAmount - array_sum($this->programmed);

        $this->supplemental_allocation_description = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where('is_supplemental', 1)->first()->description;
        $this->supplemental_allocation = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where('is_supplemental', 1)->first()->initial_amount;
        $this->sub_total_164 = $this->balance_164 + $this->supplemental_allocation;
    }

    public function calculateSubTotal($categoryGroupId)
    {
        // Return the amount associated with the given category group ID
        // if($this->amounts[$categoryGroupId] < 0)
        // {
        //     $this->amounts[$categoryGroupId] = 0;
        // }else
        // {
        //     $this->amounts[$categoryGroupId] = $this->amounts[$categoryGroupId];
        // }
        $amount = $this->allocations[$categoryGroupId] ?? 0;
        $programmed = $this->programmed[$categoryGroupId] ?? 0;
        $balance = $amount - $programmed;
        return $balance ?? 0;
    }

    public function render()
    {
        return view('livewire.w-f-p.view-supplemental-funds');
    }
}
