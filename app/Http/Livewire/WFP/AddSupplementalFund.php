<?php

namespace App\Http\Livewire\WFP;

use Livewire\Component;use App\Models\CategoryGroup;
use App\Models\FundAllocation;
use App\Models\CostCenter;
use App\Models\WpfType;
use WireUi\Traits\Actions;

class AddSupplementalFund extends Component
{
    use Actions;
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

    public function calculateSupplemental($categoryGroupId)
    {
        return $this->amounts[$categoryGroupId] ?? 0;
    }

    public function calculateTotalSupplemental()
    {
        return array_sum($this->amounts);
    }

    public function calculateSupplementalTotal($categoryGroupId)
    {
        // Return the amount associated with the given category group ID
        $allocation = $this->allocations[$categoryGroupId] ?? 0;
        $amount = $this->amounts[$categoryGroupId] ?? 0;
        $programmed = $this->programmed[$categoryGroupId] ?? 0;
        $balance = $amount - $programmed;
        $sum = $allocation + $balance;
        return $sum ?? 0;
    }

    public function calculateGrandTotal()
    {
        // Calculate the total of all amounts
       // return array_sum($this->amounts) + array_sum($this->programmed);
       $balance = array_sum($this->allocations) - array_sum($this->programmed);
       $amount = array_sum($this->amounts);
       return $balance + $amount;
    }

    public function calculateBalance($categoryGroupId)
    {
        // Return the difference between the initial amount and the amount associated with the given category group ID
        return $this->allocations[$categoryGroupId] - $this->calculateSubTotal($categoryGroupId);
    }

    public function calculateTotal()
    {
        // Calculate the total of all amounts
        return array_sum($this->allocations) - array_sum($this->programmed);
    }

    public function calculateTotalBalance()
    {
        // Calculate the total balance
        return array_sum($this->allocations) - $this->calculateTotal();
    }

    public function confirmSupplementalFund()
    {
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Do you really want to save this information?',
            'acceptLabel' => 'Yes, save it',
            'method'      => 'addSupplementalFund',
        ]);
    }

    public function addSupplementalFund()
    {
        dd($this->amounts);
    }

    public function render()
    {
        return view('livewire.w-f-p.add-supplemental-fund');
    }
}
