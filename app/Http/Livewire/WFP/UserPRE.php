<?php

namespace App\Http\Livewire\WFP;

use DB;
use App\Models\Wfp;
use Livewire\Component;
use App\Models\WfpDetail;
use App\Models\CostCenter;
use App\Models\FundClusterWFP;
use App\Models\FundAllocation;

class UserPRE extends Component
{
    public $record;
    public $cost_center;
    public $ppmp_details;
    public $total_allocated;
    public $total_programmed;
    public $balance;
    public $title;
    public $fund_allocation;

    public function mount($record,$isSupplemental)
    {
        $this->record = Wfp::find($record);
        $this->cost_center = $this->record->costCenter;
        $this->title = FundClusterWFP::find($this->record->fund_cluster_w_f_p_s_id)->name;

        if($this->record->fundClusterWfp->id === 1 || $this->record->fundClusterWfp->id === 3)
        {
            $this->fund_allocation = FundAllocation::where('cost_center_id', $this->cost_center->id)->where('initial_amount', '>', 0)
            ->where('is_supplemental', $isSupplemental)
            ->whereHas('costCenter', function($query) {
                $query->where('id', $this->record->cost_center_id)
                ->whereHas('wfp', function($query) {
                    $query->where('fund_cluster_w_f_p_s_id', $this->record->fund_cluster_w_f_p_s_id);
                });
            })
            ->get();

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) use($isSupplemental) {
                $query->where('is_supplemental', $isSupplemental)
                ->where('cost_center_id', $this->record->cost_center_id)
                      ->where('fund_cluster_w_f_p_s_id', $this->record->fund_cluster_w_f_p_s_id);
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
            $this->total_allocated = FundAllocation::where('cost_center_id', $this->cost_center->id)->where('initial_amount', '>', 0)->sum('initial_amount');
            $this->total_programmed = WfpDetail::whereHas('wfp', function($query)  use($isSupplemental) {
                $query->where('is_supplemental', $isSupplemental)->where('cost_center_id', $this->record->cost_center_id)->where('fund_cluster_w_f_p_s_id', $this->record->fund_cluster_w_f_p_s_id);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;

        }else{

            $this->fund_allocation = FundAllocation::where('is_supplemental', $isSupplemental)->whereHas('costCenter', function ($query) {
                $query->where('id', $this->cost_center->id)
                      ->whereHas('mfoFee', function ($query) {
                          $query->where('fund_cluster_w_f_p_s_id', $this->record->fund_cluster_w_f_p_s_id);
                      });
            })->get();

            // $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
            //     $query->where('cost_center_id', $this->record->cost_center_id)
            //           ->where('fund_cluster_w_f_p_s_id', $this->record->fund_cluster_w_f_p_s_id);
            // })
            // ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id') // Join with the wfp table
            // ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id') // Join with the supplies table
            // ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
            // ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
            // ->select(
            //     'wfps.cost_center_id as cost_center_id',
            //     'category_items.uacs_code as uacs',
            //     'category_items.name as item_name',
            //     \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
            //     'category_item_budgets.uacs_code as budget_uacs', // Include the related field in the select
            //     'category_item_budgets.name as budget_name', // Include the related field in the select
            //     'category_item_budgets.id as budget_id',
            //     \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs') // Total budget per budget_uacs and budget_name
            // )
            // ->groupBy('cost_center_id', 'uacs', 'budget_id', 'item_name', 'budget_uacs', 'budget_name')
            // ->get();

            $this->ppmp_details = WfpDetail::whereHas('wfp', function ($query) use($isSupplemental) {
                $query->where('is_supplemental', $isSupplemental)->where('cost_center_id', $this->record->cost_center_id)
                      ->where('fund_cluster_w_f_p_s_id', $this->record->fund_cluster_w_f_p_s_id);
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




            $this->total_allocated = FundAllocation::where('is_supplemental', $isSupplemental)->where('cost_center_id', $this->cost_center->id)
                ->where('initial_amount', '>', 0)->sum('initial_amount');
            $this->total_programmed = WfpDetail::whereHas('wfp', function($query)  use($isSupplemental) {
                $query->where('is_supplemental', $isSupplemental)->where('cost_center_id', $this->record->cost_center_id)->where('fund_cluster_w_f_p_s_id', $this->record->fund_cluster_w_f_p_s_id);
            })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();
            $this->balance = $this->total_allocated - $this->total_programmed->total_budget;
        }

    }
    public function render()
    {
        return view('livewire.w-f-p.user-p-r-e');
    }
}
