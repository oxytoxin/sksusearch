<?php

namespace App\Http\Livewire\WFP;

use Livewire\Component;use App\Models\CategoryGroup;
use App\Models\FundAllocation;
use App\Models\CostCenter;
use App\Models\WpfType;

class AddSupplementalFund extends Component
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
       // $this->amounts = array_fill_keys($this->category_groups->pluck('id')->toArray(), 0);
        foreach($this->record->wfp->where('wpf_type_id', $this->selectedType)->where('cost_center_id', $this->record->id)->get() as $wfp)
        {
            foreach($wfp->wfpDetails as $allocation)
            {
            if (!isset($this->amounts[$allocation->category_group_id])) {
                $this->amounts[$allocation->category_group_id] = 0;
            }
            $this->amounts[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
            }
        }

        foreach ($this->record->fundAllocations->where('wpf_type_id', $wfpType) as $allocation) {
            $this->allocations[$allocation->category_group_id] = $allocation->initial_amount;
        }
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
        return $this->amounts[$categoryGroupId] ?? 0;
    }

    public function calculateBalance($categoryGroupId)
    {
        // Return the difference between the initial amount and the amount associated with the given category group ID
        //return $this->allocations[$categoryGroupId] - $this->calculateSubTotal($categoryGroupId);
    }

    public function calculateTotal()
    {
        // Calculate the total of all amounts
        return array_sum($this->amounts);
    }

    public function calculateTotalBalance()
    {
        // Calculate the total balance
        return array_sum($this->allocations) - $this->calculateTotal();
    }

    public function render()
    {
        return view('livewire.w-f-p.add-supplemental-fund');
    }
}
