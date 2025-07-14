<?php

namespace App\Http\Livewire\WFP;

use App\Models\Wfp;
use Livewire\Component;

class WfpPpmp extends Component
{
    public $record;
    public $wfpDetails;
    public $program;
    public $balance;

    public $total_allocated = 0;

    public $procurements = [];

    const PROCUREMENT_IDS = [67];

    public function mount($record, $isSupplemental)
    {
        $this->record = Wfp::find($record);
        $this->wfpDetails = $this->record->wfpDetails()
                ->where('is_ppmp', 1)
                ->get();
        foreach($this->wfpDetails as $wfpDetail)
        {
            $this->program += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
        }

        $wfp = Wfp::where('cost_center_id', $this->record->cost_center_id)->where('is_supplemental', $isSupplemental)->first();
        $wfpDetails = $wfp->wfpDetails()->get();
                $programmed = 0;
                foreach ($wfpDetails as $wfpDetail) {
                    $programmed += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
                }
        $this->total_allocated = ($this->record->costCenter->fundAllocations()->where('is_supplemental', 0)->sum('initial_amount')-$programmed) +  $this->record->total_allocated_fund;

        $wfp = Wfp::where('cost_center_id', $this->record->cost_center_id)->where('is_supplemental', 1)->first();
        if($wfp){
              $this->procurements = $wfp->wfpDetails()->with(['supply.categoryItemsBudget'])->whereHas('supply',function($query){$query->whereIn('category_item_budget_id',self::PROCUREMENT_IDS);})->get();
        if(count($this->procurements) > 0){
        $proc_programmed = 0;
         foreach ($this->procurements as $procurement) {
               $proc_programmed += $procurement->total_quantity * $procurement->cost_per_unit;
        }
            $this->program = $this->program + $proc_programmed;
        }
        }
        // ->whereHas('supply',function($query){
        //     $query->whereIn('category_item_budget_id',self::PROCUREMENT_IDS);
        // })
        // $total_quantity = $this->wfpDetails->sum('total_quantity');
        // $cost_per_unit = $this->wfpDetails->sum('cost_per_unit');
        // $this->program = $total_quantity * $cost_per_unit;
        $this->balance = ($this->total_allocated - $this->program);
    }

    public function redirectBack()
    {
        return redirect()->back()->with('message', 'Your message here');
    }

    public function render()
    {
        return view('livewire.w-f-p.wfp-ppmp');
    }
}
