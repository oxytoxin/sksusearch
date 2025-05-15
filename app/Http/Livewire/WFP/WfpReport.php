<?php

namespace App\Http\Livewire\WFP;

use App\Models\Wfp;
use Livewire\Component;

class WfpReport extends Component
{
    public $record;
    public $wfpDetails;
    public $allocation;
    public $program;
    public $balance;
    public $isSupplemental;

    public function mount($record, $isSupplemental)
    {

        $this->isSupplemental = $isSupplemental;

        if($isSupplemental)
        {
        $this->record = Wfp::where('id', $record)->where('is_supplemental', 1)->first();
        $this->allocation = $this->record->costCenter->fundAllocations->where('is_supplemental', 1)->sum('initial_amount');
        $this->wfpDetails = $this->record->wfpDetails()->get();
        // $this->program = $this->wfpDetails->sum('estimated_budget');
        foreach($this->wfpDetails as $wfpDetail)
        {
            $this->program += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
        }
        // $total_quantity = $this->wfpDetails->sum('total_quantity');
        // $cost_per_unit = $this->wfpDetails->sum('cost_per_unit');
        // $this->program = $total_quantity * $cost_per_unit;
        $this->balance = $this->record->costCenter->fundAllocations->where('is_supplemental', 1)->sum('initial_amount') - $this->program;
        }else{
        $this->record = Wfp::where('id', $record)->where('is_supplemental', 0)->first();
        $this->allocation = $this->record->costCenter->fundAllocations->where('is_supplemental', 0)->sum('initial_amount');
        $this->wfpDetails = $this->record->wfpDetails()->get();
        foreach($this->wfpDetails as $wfpDetail)
        {
            $this->program += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
        }
        $this->balance = $this->allocation - $this->program;
        }

    }

    public function redirectBack()
    {
        return redirect()->back()->with('message', 'Your message here');
    }

    public function render()
    {
        return view('livewire.w-f-p.wfp-report');
    }
}
