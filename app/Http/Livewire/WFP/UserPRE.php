<?php

namespace App\Http\Livewire\WFP;

use DB;
use App\Models\Wfp;
use Livewire\Component;
use App\Models\WfpDetail;
use App\Models\CostCenter;
use App\Models\FundCluster;
use App\Models\FundAllocation;

class UserPRE extends Component
{

    public $sub_forwared_balance = [];
    public $record;
    public $cost_center;
    public $ppmp_details;
    public $forwarded_ppmp_details;

    public $total_allocated;
    public $total_programmed;
    public $balance;

    public $forwarded_balance = 0;

    public $title;
    public $fund_allocation;

    public $wfpType = null;
    public $costCenterId = null;
    public $supplementalQuarterId = null;

    protected $queryString = ["wfpType", "costCenterId", "supplementalQuarterId"];

    public $_164 = [
        'forwarded_balance' => 0,
        'total_programmed' => 0
    ];

    public function mount($record, $isSupplemental)
    {
        $this->record = Wfp::find($record);
        $this->cost_center = $this->record->costCenter;
        $this->title = FundCluster::find($this->record->fund_cluster_id)->name;

        if (in_array($this->record->fundClusterWfp->id, [1, 3, 9])) {
            if ($isSupplemental) {

                $temp_fund_allocation = FundAllocation::where('wpf_type_id', $this->wfpType)
                    ->where('cost_center_id', $this->costCenterId)
                    ->where('initial_amount', '>', 0)
                    ->whereHas('costCenter', function ($query) {
                        $query->whereHas('wfp', function ($query) {
                            $query->where('wpf_type_id', $this->wfpType)
                                ->where('fund_cluster_id', $this->record->fund_cluster_id);
                        });
                    })
                    ->when(!is_null($this->supplementalQuarterId), function ($query) {
                        $query->where(function ($query) {
                            $query->where('is_supplemental', 0)
                                ->orWhere(function ($query) {
                                    $query->where('supplemental_quarter_id', '!=', null)
                                        ->where('supplemental_quarter_id', '<=', $this->supplementalQuarterId);
                                });
                        });
                    })
                    ->get();

                $this->forwarded_ppmp_details = $temp_fund_allocation->filter(function ($item) {
                    return $item->supplemental_quarter_id != $this->supplementalQuarterId;
                });

                $wpfDetails = WfpDetail::whereHas('wfp', function ($query) {
                    $query->where('cost_center_id', $this->costCenterId)
                        ->where('wpf_type_id', $this->wfpType)
                        ->where('fund_cluster_id', $this->record->fund_cluster_id)
                        ->where(function ($query) {
                            $query->where('is_supplemental', 0)
                                ->orWhere(function ($query) {
                                    $query->where('supplemental_quarter_id', '!=', null)
                                        ->where('supplemental_quarter_id', '<=', $this->supplementalQuarterId);
                                });
                        });
                })->with('wfp')->get();

                $this->sub_forwared_balance = $wpfDetails->filter(function ($item) {
                    return $item->wfp->supplemental_quarter_id != $this->supplementalQuarterId;
                })->map(function ($item) {
                    return [
                        ...$item->toArray(),
                        'balance_amount' => ($item->cost_per_unit * $item->total_quantity)
                    ];
                });

                $this->forwarded_balance = $this->forwarded_ppmp_details->sum('initial_amount') - $this->sub_forwared_balance->sum('balance_amount');

                $supplemental_fund_allocation = $temp_fund_allocation->where(
                    'supplemental_quarter_id',
                    $this->supplementalQuarterId
                );

                $non_supplemental_fund_allocation = $temp_fund_allocation->filter(function ($item) use (
                    $supplemental_fund_allocation
                ) {
                    return $item->supplemental_quarter_id !== $this->supplementalQuarterId;
                });

                $this->fund_allocation = $temp_fund_allocation->filter(function ($item) use (
                    $supplemental_fund_allocation,
                    $non_supplemental_fund_allocation
                ) {
                    if ($item->is_supplemental === 1) {
                        return $item;
                    } else {
                        if ($item->is_supplemental === 0 && !is_null($non_supplemental_fund_allocation->where(
                            'category_group_id',
                            $item->category_group_id
                        )->first()) && $supplemental_fund_allocation->where(
                            'category_group_id',
                            $item->category_group_id
                        )->first() === null) {
                            return $item;
                        } else {
                            return null;
                        }
                    }
                })->filter(function ($item) use ($supplemental_fund_allocation, $non_supplemental_fund_allocation) {
                    return !is_null($item);
                });
            } else {
                $this->fund_allocation = FundAllocation::where('cost_center_id', $this->cost_center->id)
                    ->where('wpf_type_id', $this->wfpType)
                    ->where('cost_center_id', $this->costCenterId)
                    ->where('initial_amount', '>', 0)
                    ->where('is_supplemental', $isSupplemental)
                    ->whereHas('costCenter', function ($query) {
                        $query->whereHas('wfp', function ($query) {
                            $query->where('wpf_type_id', $this->wfpType)->where(
                                'fund_cluster_id',
                                $this->record->fund_cluster_id
                            );
                        });
                    })
                    ->get();
            }
            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) use ($isSupplemental) {
                $query->when($this->wfpType, function ($query) {
                    $query->where('wpf_type_id', $this->wfpType);
                })->where('is_supplemental', $isSupplemental)
                    ->where('cost_center_id', $this->record->cost_center_id)
                    ->where('fund_cluster_id', $this->record->fund_cluster_id);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->select(
                    'wfps.cost_center_id as cost_center_id',
                    'wfp_details.category_group_id as category_group_id',
                    'category_items.uacs_code as uacs',
                    'category_items.name as item_name',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
                )
                ->groupBy('cost_center_id', 'category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
                ->get();
            $this->total_allocated = $this->fund_allocation->sum('initial_amount');

            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) use ($isSupplemental) {
                $query->when($this->wfpType, function ($query) {
                    $query->where('wpf_type_id', $this->wfpType);
                })->where('is_supplemental', $isSupplemental)->where(
                    'cost_center_id',
                    $this->record->cost_center_id
                )->where(
                    'fund_cluster_id',
                    $this->record->fund_cluster_id
                );
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;
        } else {
            $this->fund_allocation = FundAllocation::where(
                'supplemental_quarter_id',
                $this->supplementalQuarterId
            )->whereHas('costCenter', function ($query) {
                $query->where('id', $this->cost_center->id)
                    ->whereHas('mfoFee', function ($query) {
                        $query->where('fund_cluster_id', $this->record->fund_cluster_id);
                    });
            })->get();
            if ($isSupplemental) {
                $temp_total_allocated = FundAllocation::where('fund_cluster_id',$this->record->fund_cluster_id)
                    ->where('wpf_type_id', $this->wfpType)
                   ->where(function ($query) {
                        $query->when(!is_null($this->supplementalQuarterId), function ($query) {
                          if ($this->supplementalQuarterId == 1) {
                                $query->where('is_supplemental', 0);
                            } else {
                                $query->where('is_supplemental', 0)->orWhere(function ($query) {
                                    $query->where('supplemental_quarter_id', '!=', null)->where('supplemental_quarter_id', '<', $this->supplementalQuarterId);
                                });
                            }
                        });
                   })
                    ->where(
                        'cost_center_id',
                        $this->cost_center->id
                    )
                    ->where('initial_amount', '>', 0)->sum('initial_amount');

                $this->_164['total_programmed'] = WfpDetail::whereHas('wfp', function ($query) {
                    $query->when($this->wfpType, function ($query) {
                        $query->where('wpf_type_id', $this->wfpType);
                    })
                       ->where(function($query) {
                           $query->when(!is_null($this->supplementalQuarterId), function ($query) {
                            if ($this->supplementalQuarterId == 1) {
                                $query->where('is_supplemental', 0);
                            } else {
                                $query->where('is_supplemental', 0)->orWhere(function ($query) {
                                    $query->where('supplemental_quarter_id', '!=', null)->where('supplemental_quarter_id', '<', $this->supplementalQuarterId);
                                });
                            }
                        });
                       })
                        ->where(
                            'cost_center_id',
                            $this->record->cost_center_id
                        )->where(
                            'fund_cluster_id',
                            $this->record->fund_cluster_id
                        );
                })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
                $this->_164['balance'] = $temp_total_allocated - $this->_164['total_programmed']->total_budget;
            }
            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) use ($isSupplemental) {
                $query->when($this->wfpType, function ($query) {
                    $query->where('wpf_type_id', $this->wfpType);
                })->where('supplemental_quarter_id', $this->supplementalQuarterId)->where(
                    'cost_center_id',
                    $this->record->cost_center_id
                )
                    ->where('fund_cluster_id', $this->record->fund_cluster_id);
            })
                ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
                ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
                ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
                ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
                ->select(
                    'wfps.cost_center_id as cost_center_id',
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
                    'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
                    'category_item_budgets.name as budget_name', // Include the related field in the select
                    \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
                )
                ->groupBy('cost_center_id', 'budget_uacs', 'budget_name')
                ->get();


            $this->total_allocated = FundAllocation::when(!is_null($this->supplementalQuarterId), function ($query) {
                if ($this->supplementalQuarterId == 1) {
                    $query->where('is_supplemental', 0);
                } else {
                    $query->where('supplemental_quarter_id', $this->supplementalQuarterId - 1);
                }
            })->where('cost_center_id', $this->cost_center->id)
                ->where('initial_amount', '>', 0)->sum('initial_amount');
            $this->total_programmed = WfpDetail::whereHas('wfp', function ($query) use ($isSupplemental) {
                $query->when($this->wfpType, function ($query) {
                    $query->where('wpf_type_id', $this->wfpType);
                })->when(!is_null($this->supplementalQuarterId), function ($query) {
                    if ($this->supplementalQuarterId == 1) {
                        $query->where('is_supplemental', 0);
                    } else {
                        $query->where('supplemental_quarter_id', $this->supplementalQuarterId - 1);
                    }
                })->where(
                    'cost_center_id',
                    $this->record->cost_center_id
                )->where(
                    'fund_cluster_id',
                    $this->record->fund_cluster_id
                );
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;
        }
    }

    public function render()
    {
        return view('livewire.w-f-p.user-p-r-e');
    }
}
