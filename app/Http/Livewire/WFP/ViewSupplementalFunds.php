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

    public $allocations_suplemental;

    public $supplementalQuarterId = null;

    protected $queryString = ['supplementalQuarterId'];

    public $supplementals = [];

    public function mount($record, $wfpType, $isForwarded)
    {
        $_wfpType = WpfType::find($wfpType);

        if ($isForwarded) {
               $this->record = CostCenter::find($record)->load(['fundAllocations' => function ($q) use ($wfpType) {
                $q->where('wpf_type_id', $wfpType)->where(function ($q) {
                    $q->where('is_supplemental', 0)->orWhere(function ($q) {
                        if (!is_null($this->supplementalQuarterId)) {
                            $q->whereNotNull('supplemental_quarter_id')
                                ->where('supplemental_quarter_id', '<=', $this->supplementalQuarterId);
                        } else {
                            $q->where('is_supplemental', 1);
                        }
                    });
                })->with(['supplementalQuarter']);
            }, 'wfp' => function ($q)  use ($wfpType) {
                $q->where('wpf_type_id', $wfpType)->where(function ($q) {
                    $q->where('is_supplemental', 0)->orWhere(function ($q) {
                        if (!is_null($this->supplementalQuarterId)) {
                            $q->whereNotNull('supplemental_quarter_id')->where('supplemental_quarter_id', '<=', $this->supplementalQuarterId);
                        }
                    });
                })->with('wfpDetails');
            }]);
            foreach ($this->record->fundAllocations as $fund) {
                $current_balance = 0;
                $current_programmed = 0;
                $current_allocation = 0;

                $next_allocation = 0;
                $total_allocation = 0;

                if ($fund->supplemental_quarter_id === null) {
                    foreach ($this->record->wfp->where('is_supplemental', 0) as $wfp) {
                        foreach ($wfp->wfpDetails as $wfpDetails) {
                            $current_programmed += $wfpDetails->total_quantity * $wfpDetails->cost_per_unit;
                        }
                    }
                    $current_allocation = $this->record->wfp->where('is_supplemental', 0)->sum('total_allocated_fund');
                    $current_balance =  $current_allocation - $current_programmed;
                    $next_allocation = $this->record->fundAllocations->filter(function ($a) {
                        return $a->supplemental_quarter_id === 1;
                    })->sum('initial_amount');
                    $total_allocation =  $next_allocation + $current_balance;
                } else {

                    foreach (
                        $this->record->wfp->filter(function ($w) use ($fund) {
                            return $w->supplemental_quarter_id <= $fund->supplemental_quarter_id;
                        }) as $wfp
                    ) {
                        foreach ($wfp->wfpDetails as $wfpDetails) {
                            $current_programmed += $wfpDetails->total_quantity * $wfpDetails->cost_per_unit;
                        }
                    }

                    $current_allocation = $this->record->fundAllocations->filter(function ($w) use ($fund) {
                        return $w->supplemental_quarter_id <= $fund->supplemental_quarter_id;
                    })->sum('initial_amount');


                    $current_balance = $current_allocation - $current_programmed;



                    $next_allocation =  $this->record->fundAllocations->filter(function ($a) use ($fund) {
                        return $a->supplemental_quarter_id === (int) $fund->supplemental_quarter_id + 1;
                    })->sum('initial_amount');

                    $total_allocation = $next_allocation + $current_balance;
                }

                $q_name = "WFP";
                if (!is_null($fund->supplementalQuarter)) {
                    $q_name = $fund->supplementalQuarter->name;
                }

                $this->supplementals[] = [
                    'description' => $_wfpType->description . " - " . $q_name,
                    'balance' => $current_balance,
                    'current_allocation' => $next_allocation,
                    'total_allocations' => $total_allocation,
                ];
            }

            if(!in_array($this->record->fund_cluster_w_f_p_id,[1,3,9])){
                $this->record = CostCenter::find($record)->load(['wfp']);
            $initialNonSupplementalFundAllocation = $this->record->fundAllocations->where('wpf_type_id', $wfpType)
                ->when(!is_null($this->supplementalQuarterId), function ($query) {
                    if ($this->supplementalQuarterId == 1) {
                        $query->where('is_supplemental', 0);
                    } else {
                        $query->where('supplemental_quarter_id', (int)$this->supplementalQuarterId - 1);
                    }
                })->first();
            $this->selectedType =  $initialNonSupplementalFundAllocation->wpf_type_id ?? null;
            $this->fundInitialAmount = $initialNonSupplementalFundAllocation->initial_amount ?? 0;
            $this->fund_description = 'No Fund Allocation';
            $this->supplemental_quarter = SupplementalQuarter::where('id', $this->supplementalQuarterId)->first();

            $workFinancialPlans = $this->record->wfp()->where('wpf_type_id', $this->selectedType)->where('cost_center_id', $this->record->id)->with(['wfpDetails'])->get();

            if ($workFinancialPlans) {

                if ($this->supplementalQuarterId == 1) {
                    foreach ($workFinancialPlans->where('is_supplemental', 0) as $wfp) {
                        foreach ($wfp->wfpDetails as $allocation) {
                            if (!isset($this->programmed[$allocation->category_group_id])) {
                                $this->programmed[$allocation->category_group_id] = 0;
                            }
                            $this->programmed[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                        }
                    }
                } else {
                    foreach ($workFinancialPlans->where('supplemental_quarter_id', (int)$this->supplementalQuarterId - 1) as $wfp) {
                        foreach ($wfp->wfpDetails as $allocation) {
                            if (!isset($this->programmed[$allocation->category_group_id])) {
                                $this->programmed[$allocation->category_group_id] = 0;
                            }
                            $this->programmed[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                        }
                    }
                }

                foreach ($workFinancialPlans->where('supplemental_quarter_id', $this->supplementalQuarterId) as $wfp) {
                    foreach ($wfp->wfpDetails as $allocation) {
                        if (!isset($this->programmed_supplemental[$allocation->category_group_id])) {
                            $this->programmed_supplemental[$allocation->category_group_id] = 0;
                        }
                        $this->programmed_supplemental[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                    }
                }
            }
            $costCenterFundAllocation = $this->record->fundAllocations->where('wpf_type_id', $wfpType);
            if ($this->supplementalQuarterId == 1) {
                foreach ($costCenterFundAllocation->where('is_supplemental', 0) as $allocation) {
                    $this->allocations[$allocation->category_group_id] = $allocation->initial_amount;
                }
            } else {
                foreach ($costCenterFundAllocation->where('supplemental_quarter_id', (int)$this->supplementalQuarterId - 1) as $allocation) {
                    $this->allocations[$allocation->category_group_id] = $allocation->initial_amount;
                }
            }

            foreach ($costCenterFundAllocation->where('supplemental_quarter_id', $this->supplementalQuarterId) as $allocation) {
                $this->allocations_suplemental[$allocation->category_group_id] = $allocation->initial_amount;
            }

            $this->balances = collect($this->allocations)->map(function ($allocation, $categoryGroupId) {
                return (float)$allocation - (float)$this->calculateSubTotal($categoryGroupId);
            });
            }

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
            $this->record = CostCenter::find($record)->load(['fundAllocations' => function ($q) use ($wfpType) {
                $q->where('wpf_type_id', $wfpType)->where(function ($q) {
                    $q->where('is_supplemental', 0)->orWhere(function ($q) {
                        if (!is_null($this->supplementalQuarterId)) {
                            $q->whereNotNull('supplemental_quarter_id')
                                ->where('supplemental_quarter_id', '<=', $this->supplementalQuarterId);
                        } else {
                            $q->where('is_supplemental', 1);
                        }
                    });
                })->with(['supplementalQuarter']);
            }, 'wfp' => function ($q)  use ($wfpType) {
                $q->where('wpf_type_id', $wfpType)->where(function ($q) {
                    $q->where('is_supplemental', 0)->orWhere(function ($q) {
                        if (!is_null($this->supplementalQuarterId)) {
                            $q->whereNotNull('supplemental_quarter_id')->where('supplemental_quarter_id', '<=', $this->supplementalQuarterId);
                        }
                    });
                })->with('wfpDetails');
            }]);
            foreach ($this->record->fundAllocations as $fund) {
                $current_balance = 0;
                $current_programmed = 0;
                $current_allocation = 0;

                $next_allocation = 0;
                $total_allocation = 0;

                if ($fund->supplemental_quarter_id === null) {
                    foreach ($this->record->wfp->where('is_supplemental', 0) as $wfp) {
                        foreach ($wfp->wfpDetails as $wfpDetails) {
                            $current_programmed += $wfpDetails->total_quantity * $wfpDetails->cost_per_unit;
                        }
                    }
                    $current_allocation = $this->record->wfp->where('is_supplemental', 0)->sum('total_allocated_fund');
                    $current_balance =  $current_allocation - $current_programmed;
                    $next_allocation = $this->record->fundAllocations->filter(function ($a) {
                        return $a->supplemental_quarter_id === 1;
                    })->sum('initial_amount');
                    $total_allocation =  $next_allocation + $current_balance;
                } else {

                    foreach (
                        $this->record->wfp->filter(function ($w) use ($fund) {
                            return $w->supplemental_quarter_id <= $fund->supplemental_quarter_id;
                        }) as $wfp
                    ) {
                        foreach ($wfp->wfpDetails as $wfpDetails) {
                            $current_programmed += $wfpDetails->total_quantity * $wfpDetails->cost_per_unit;
                        }
                    }

                    $current_allocation = $this->record->fundAllocations->filter(function ($w) use ($fund) {
                        return $w->supplemental_quarter_id <= $fund->supplemental_quarter_id;
                    })->sum('initial_amount');


                    $current_balance = $current_allocation - $current_programmed;



                    $next_allocation =  $this->record->fundAllocations->filter(function ($a) use ($fund) {
                        return $a->supplemental_quarter_id === (int) $fund->supplemental_quarter_id + 1;
                    })->sum('initial_amount');

                    $total_allocation = $next_allocation + $current_balance;
                }

                $q_name = "WFP";
                if (!is_null($fund->supplementalQuarter)) {
                    $q_name = $fund->supplementalQuarter->name;
                }

                $this->supplementals[] = [
                    'description' => $_wfpType->description . " - " . $q_name,
                    'balance' => $current_balance,
                    'current_allocation' => $next_allocation,
                    'total_allocations' => $total_allocation,
                ];
            }

            $initialNonSupplementalFundAllocation = $this->record->fundAllocations->where('is_supplemental', 0)->first();
            $this->selectedType =  $initialNonSupplementalFundAllocation->wpf_type_id;
            $this->fundInitialAmount = $initialNonSupplementalFundAllocation->initial_amount;
            $this->fund_description = $this->record->fundAllocations->where('is_supplemental', 0)->first()->description;
            $this->supplemental_quarter = SupplementalQuarter::where('id', $this->supplementalQuarterId)->first();

            $workFinancialPlans = $this->record->wfp()->where('wpf_type_id', $this->selectedType)->where('cost_center_id', $this->record->id)->with(['wfpDetails'])->get();

            if ($this->supplementalQuarterId == 1) {
                foreach ($workFinancialPlans->where('is_supplemental', 0) as $wfp) {
                    foreach ($wfp->wfpDetails as $allocation) {
                        if (!isset($this->programmed[$allocation->category_group_id])) {
                            $this->programmed[$allocation->category_group_id] = 0;
                        }
                        $this->programmed[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                    }
                }
            } else {
                foreach ($workFinancialPlans->where('supplemental_quarter_id', $this->supplementalQuarterId - 1) as $wfp) {
                    foreach ($wfp->wfpDetails as $allocation) {
                        if (!isset($this->programmed[$allocation->category_group_id])) {
                            $this->programmed[$allocation->category_group_id] = 0;
                        }
                        $this->programmed[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                    }
                }
            }

            foreach ($workFinancialPlans->where('supplemental_quarter_id', $this->supplementalQuarterId) as $wfp) {
                foreach ($wfp->wfpDetails as $allocation) {
                    if (!isset($this->programmed_supplemental[$allocation->category_group_id])) {
                        $this->programmed_supplemental[$allocation->category_group_id] = 0;
                    }
                    $this->programmed_supplemental[$allocation->category_group_id] += ($allocation->total_quantity * $allocation->cost_per_unit);
                }
            }

            $costCenterFundAllocation = $this->record->fundAllocations->where('wpf_type_id', $wfpType);
            if ($this->supplementalQuarterId == 1) {
                foreach ($costCenterFundAllocation->where('is_supplemental', 0) as $allocation) {
                    $this->allocations[$allocation->category_group_id] = $allocation->initial_amount;
                }
            } else {
                foreach ($costCenterFundAllocation->where('supplemental_quarter_id', (int)$this->supplementalQuarterId - 1) as $allocation) {
                    $this->allocations[$allocation->category_group_id] = $allocation->initial_amount;
                }
            }

            foreach ($costCenterFundAllocation->where('supplemental_quarter_id', $this->supplementalQuarterId) as $allocation) {
                $this->allocations_suplemental[$allocation->category_group_id] = $allocation->initial_amount;
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
        return $this->allocations_suplemental[$categoryGroupId] ?? 0;
    }

    public function calculateTotalSupplemental()
    {
        return empty($this->allocations_suplemental) ? 0 : array_sum($this->allocations_suplemental);
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
        $amount = empty($this->allocations_suplemental) ? 0 : array_sum($this->allocations_suplemental);
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
