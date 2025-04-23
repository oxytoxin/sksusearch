<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\FundAllocation;
use App\Models\WfpDetail;
use DB;

class PreExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $fund_allocation;
    public $ppmp_details;
    public $total_allocated;
    public $total_programmed;
    public $balance;
    public $selectedType;

    public function __construct($selectedType, $fund_allocation, $ppmp_details, $total_allocated, $total_programmed, $balance)
    {
        $this->fund_allocation = $fund_allocation;
        $this->ppmp_details = $ppmp_details;
        $this->total_allocated = $total_allocated;
        $this->total_programmed = $total_programmed;
        $this->balance = $balance;
        $this->selectedType = $selectedType;
    }

    public function view(): View
    {
        // $ppmp_details = WfpDetail::whereHas('wfp', function ($query) {
        //     $query->where('fund_cluster_w_f_p_s_id', 1);
        // })
        // ->join('wfps', 'wfp_details.wfp_id', '=', 'wfps.id')
        // ->join('supplies', 'wfp_details.supply_id', '=', 'supplies.id')
        // ->join('category_item_budgets', 'supplies.category_item_budget_id', '=', 'category_item_budgets.id')
        // ->join('category_items', 'supplies.category_item_id', '=', 'category_items.id')
        // ->select(
        //     'wfp_details.category_group_id as category_group_id',
        //     'category_items.uacs_code as uacs',
        //     'category_items.name as item_name',
        //     \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget'),
        //     'category_item_budgets.uacs_code as budget_uacs',
        //     'category_item_budgets.name as budget_name',
        //     \DB::raw('SUM(wfp_details.cost_per_unit * wfp_details.total_quantity) as total_budget_per_uacs')
        // )
        // ->groupBy('category_group_id', 'uacs', 'item_name', 'budget_uacs', 'budget_name')
        // ->get();

        // $total_allocated = $this->fund_allocation->sum('total_allocated');

        // $total_programmed = WfpDetail::whereHas('wfp', function($query) {
        //     $query->where('fund_cluster_w_f_p_s_id', 1);
        // })->select(DB::raw('SUM(cost_per_unit * total_quantity) as total_budget'))->first();

        // $balance = $total_allocated - $total_programmed->total_budget;

        return view('exports.101', [
            'fund_allocation' => $this->fund_allocation,
            'ppmp_details' => $this->ppmp_details,
            'total_allocated' => $this->total_allocated,
            'total_programmed' => $this->total_programmed,
            'balance' => $this->balance,
            'selectedType' => $this->selectedType,
        ]);
    }
}
