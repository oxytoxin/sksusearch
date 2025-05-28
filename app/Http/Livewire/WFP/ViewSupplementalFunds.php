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
    public $programmed_supplemental = [];
    public $balances = [];
    public $supplemental_quarter;


    //for 164
    public $supplemental_allocation;
    public $supplemental_allocation_description;
    public $balance_164;
    public $sub_total_164;
    public $balance_164_q1;

    public $allocations_non_suplemental;

    public function mount($record, $wfpType, $isForwarded)
    {

        // $this->amounts = array_fill_keys($this->category_groups->pluck('id')->toArray(), 0);
        if ($isForwarded) {
            // $this->record = CostCenter::find($record);
            // $this->category_groups = CategoryGroup::where('is_active', 1)->get();
            // $this->category_groups_supplemental = CategoryGroup::whereHas('fundAllocations', function ($query) {
            //     $query->where('cost_center_id', $this->record->id)->where('is_supplemental', 0)->where('initial_amount', '>', 0);
            // })->where('is_active', 1)->get();
            // $this->wfp_type = WpfType::all();

            // if ($this->record->fund_allocations()->where('is_supplemental', 0)->exists()) {
            //     $this->selectedType = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where('is_supplemental', 0)->first()->wpf_type_id;
            //     $this->fundInitialAmount = $this->record->fundAllocations->where('wpf_type_id', $this->selectedType)->where('is_supplemental', 0)->first()->initial_amount;
            //     $this->fund_description = $this->record->fundAllocations->where('is_supplemental', 0)->first()->description;
            //     $this->supplemental_quarter = SupplementalQuarter::where('is_active', 1)->first();

            //     $this->balance_164 = $this->fundInitialAmount;

            //     $this->supplemental_allocation_description = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where('is_supplemental', 1)->first()->description;
            //     $this->supplemental_allocation = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where('is_supplemental', 1)->first()->initial_amount;
            //     $this->sub_total_164 = $this->balance_164 + $this->supplemental_allocation;
            //     $this->balance_164_q1 = $this->sub_total_164 - array_sum($this->programmed_supplemental);
            // } else {
            //     $this->fundInitialAmount = 0;
            //     $this->fund_description = 'No Fund Allocation';
            //     $this->supplemental_quarter = SupplementalQuarter::where('is_active', 1)->first();

            //     $this->balance_164 = $this->fundInitialAmount;
            //     $this->supplemental_allocation_description = $this->record->fundAllocations->where('is_supplemental', 1)->first()->description;
            //     $this->supplemental_allocation = $this->record->fundAllocations->where('is_supplemental', 1)->first()->initial_amount;
            //     $this->sub_total_164 = $this->balance_164 + $this->supplemental_allocation;
            //     $this->balance_164_q1 = $this->sub_total_164 - array_sum($this->programmed_supplemental);
            // }
            $this->record = CostCenter::find($record)->load(['wfp']);
            // $this->category_groups_supplemental = CategoryGroup::whereHas('fundAllocations', function ($query) {
            //     $query->where('cost_center_id', $this->record->id)->where('is_supplemental', 0)->where('initial_amount', '>', 0);
            // })->where('is_active', 1)->get();
            // $this->wfp_type = WpfType::all();

            $initialNonSupplementalFundAllocation = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where('is_supplemental', 0)->first();
            $this->selectedType =  $initialNonSupplementalFundAllocation->wpf_type_id ?? null;
            $this->fundInitialAmount = $initialNonSupplementalFundAllocation->initial_amount ?? 0;
            $this->fund_description = 'No Fund Allocation';
            $this->supplemental_quarter = SupplementalQuarter::where('is_active', 1)->first();

            $workFinancialPlans = $this->record->wfp?->where('wpf_type_id', $this->selectedType)->where('cost_center_id', $this->record->id)->with(['wfpDetails'])->get();

            if ($workFinancialPlans) {
                foreach ($workFinancialPlans->where('is_supplemental', 0) as $wfp) {
                    foreach ($wfp->wfpDetails as $allocation) {
                        if (!isset($this->programmed[$allocation->category_group_id])) {
                            $this->programmed[$allocation->category_group_id] = 0;
                        }
                        $this->programmed[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                    }
                }

                foreach ($workFinancialPlans->where('is_supplemental', 1) as $wfp) {
                    foreach ($wfp->wfpDetails as $allocation) {
                        if (!isset($this->programmed_supplemental[$allocation->category_group_id])) {
                            $this->programmed_supplemental[$allocation->category_group_id] = 0;
                        }
                        $this->programmed_supplemental[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                    }
                }
            }




            $costCenterFundAllocation = $this->record->fundAllocations->where('wpf_type_id', $wfpType);

            foreach ($costCenterFundAllocation->where('is_supplemental', 0) as $allocation) {
                $this->allocations[$allocation->category_group_id] = $allocation->initial_amount;
            }

            foreach ($costCenterFundAllocation->where('is_supplemental', 1) as $allocation) {
                $this->allocations_non_suplemental[$allocation->category_group_id] = $allocation->initial_amount;
            }

            $this->balances = collect($this->allocations)->map(function ($allocation, $categoryGroupId) {
                return (float)$allocation - (float)$this->calculateSubTotal($categoryGroupId);
            });

            $this->category_groups = CategoryGroup::where('is_active', 1)->get()->map(function ($categoryGroup) {
                return [
                    'id' => $categoryGroup->id,
                    'name' => $categoryGroup->name,
                    'balance' => $this->calculateSubTotal($categoryGroup->id),
                    'supplemental' => $this->calculateSupplemental($categoryGroup->id),
                    'sub_total' => $this->calculateSubTotal($categoryGroup->id) + $this->calculateSupplemental($categoryGroup->id),
                ];
            });

            $this->balance_164 = $this->fundInitialAmount - array_sum($this->programmed);
            $this->supplemental_allocation_description = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where('is_supplemental', 1)->first()->description;
            $this->supplemental_allocation = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where('is_supplemental', 1)->first()->initial_amount;
            $this->sub_total_164 = $this->balance_164 + $this->supplemental_allocation;
            $this->balance_164_q1 = $this->sub_total_164 - array_sum($this->programmed_supplemental);
        } else {
            $this->record = CostCenter::find($record);
            // $this->category_groups_supplemental = CategoryGroup::whereHas('fundAllocations', function ($query) {
            //     $query->where('cost_center_id', $this->record->id)->where('is_supplemental', 0)->where('initial_amount', '>', 0);
            // })->where('is_active', 1)->get();
            // $this->wfp_type = WpfType::all();

            $initialNonSupplementalFundAllocation = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where('is_supplemental', 0)->first();
            $this->selectedType =  $initialNonSupplementalFundAllocation->wpf_type_id;
            $this->fundInitialAmount = $initialNonSupplementalFundAllocation->initial_amount;
            $this->fund_description = $this->record->fundAllocations->where('is_supplemental', 0)->first()->description;
            $this->supplemental_quarter = SupplementalQuarter::where('is_active', 1)->first();

            $workFinancialPlans = $this->record->wfp->where('wpf_type_id', $this->selectedType)->where('cost_center_id', $this->record->id)->with(['wfpDetails'])->get();

            foreach ($workFinancialPlans->where('is_supplemental', 0) as $wfp) {
                foreach ($wfp->wfpDetails as $allocation) {
                    if (!isset($this->programmed[$allocation->category_group_id])) {
                        $this->programmed[$allocation->category_group_id] = 0;
                    }
                    $this->programmed[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                }
            }

            foreach ($workFinancialPlans->where('is_supplemental', 1) as $wfp) {
                foreach ($wfp->wfpDetails as $allocation) {
                    if (!isset($this->programmed_supplemental[$allocation->category_group_id])) {
                        $this->programmed_supplemental[$allocation->category_group_id] = 0;
                    }
                    $this->programmed_supplemental[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                }
            }
            $costCenterFundAllocation = $this->record->fundAllocations->where('wpf_type_id', $wfpType);

            foreach ($costCenterFundAllocation->where('is_supplemental', 0) as $allocation) {
                $this->allocations[$allocation->category_group_id] = $allocation->initial_amount;
            }

            foreach ($costCenterFundAllocation->where('is_supplemental', 1) as $allocation) {
                $this->allocations_non_suplemental[$allocation->category_group_id] = $allocation->initial_amount;
            }

            $this->balances = collect($this->allocations)->map(function ($allocation, $categoryGroupId) {
                return (float)$allocation - (float)$this->calculateSubTotal($categoryGroupId);
            });

            $this->category_groups = CategoryGroup::where('is_active', 1)->get()->map(function ($categoryGroup) {
                return [
                    'id' => $categoryGroup->id,
                    'name' => $categoryGroup->name,
                    'balance' => $this->calculateSubTotal($categoryGroup->id),
                    'supplemental' => $this->calculateSupplemental($categoryGroup->id),
                    'sub_total' => $this->calculateSubTotal($categoryGroup->id) + $this->calculateSupplemental($categoryGroup->id),
                ];
            });

            $this->balance_164 = $this->fundInitialAmount - array_sum($this->programmed);
            $this->supplemental_allocation_description = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where('is_supplemental', 1)->first()->description;
            $this->supplemental_allocation = $this->record->fundAllocations->where('wpf_type_id', $wfpType)->where('is_supplemental', 1)->first()->initial_amount;
            $this->sub_total_164 = $this->balance_164 + $this->supplemental_allocation;
            $this->balance_164_q1 = $this->sub_total_164 - array_sum($this->programmed_supplemental);
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
        $amount = $this->allocations[$categoryGroupId] ?? 0;
        $programmed = $this->programmed[$categoryGroupId] ?? 0;
        $balance =   $amount - $programmed;
        return $balance ?? 0;
    }

    public function calculateSupplemental($categoryGroupId)
    {
        return $this->allocations_non_suplemental[$categoryGroupId] ?? 0;
    }

    public function calculateTotalSupplemental()
    {
        return empty($this->allocations_non_suplemental) ? 0 : array_sum($this->allocations_non_suplemental);
    }

    public function calculateSupplementalTotal($categoryGroupId)
    {
        // Return the amount associated with the given category group ID
        $allocation = $this->allocations[$categoryGroupId] ?? 0;
        $amount = $this->amounts[$categoryGroupId] ?? 0;
        $programmed = $this->programmed[$categoryGroupId] ?? 0;
        $balance =  $programmed - $amount;
        $sum = $allocation + $balance;
        return $sum ?? 0;
    }

    public function calculateGrandTotal()
    {
        // Calculate the total of all amounts
        // return array_sum($this->amounts) + array_sum($this->programmed);
        $balance = array_sum($this->allocations) - array_sum($this->programmed);
        $amount = empty($this->allocations_non_suplemental) ? 0 : array_sum($this->allocations_non_suplemental);
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

    public function render()
    {
        return view('livewire.w-f-p.view-supplemental-funds');
    }
}
