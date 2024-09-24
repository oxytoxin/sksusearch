<?php

namespace App\Http\Livewire\WFP;

use DB;
use App\Models\Wfp;
use Livewire\Component;
use App\Models\WfpDetail;
use App\Models\CostCenter;
use App\Models\FundClusterWFP;

class UserPRE extends Component
{
    public $record;
    public $ppmp_details;
    public $total;
    public $title;

    public function mount($record)
    {
        $this->record = Wfp::find($record);
        $this->title = FundClusterWFP::find($this->record->fund_cluster_w_f_p_s_id)->name;
        $this->ppmp_details = WfpDetail::whereHas('wfp', function($query) {
            $query->where('cost_center_id', $this->record->cost_center_id)->where('fund_cluster_w_f_p_s_id', $this->record->fund_cluster_w_f_p_s_id);
        })->select('category_item_id', \DB::raw('SUM(estimated_budget) as total_budget'))
        ->groupBy('category_item_id')
        ->get();
        $this->total = WfpDetail::whereHas('wfp', function($query) {
            $query->where('cost_center_id', $this->record->cost_center_id)->where('fund_cluster_w_f_p_s_id', $this->record->fund_cluster_w_f_p_s_id);
        })->select(DB::raw('SUM(estimated_budget) as total_budget'))->first();
    }
    public function render()
    {
        return view('livewire.w-f-p.user-p-r-e');
    }
}
