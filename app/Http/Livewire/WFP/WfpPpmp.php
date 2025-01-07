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

    public function mount($record)
    {
        $this->record = Wfp::find($record);
        $this->wfpDetails = $this->record->wfpDetails()->where('is_ppmp', 1)->get();
        foreach($this->wfpDetails as $wfpDetail)
        {
            $this->program += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
        }
        // $total_quantity = $this->wfpDetails->sum('total_quantity');
        // $cost_per_unit = $this->wfpDetails->sum('cost_per_unit');
        // $this->program = $total_quantity * $cost_per_unit;
        $this->balance = $this->record->total_allocated_fund - $this->program;
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
