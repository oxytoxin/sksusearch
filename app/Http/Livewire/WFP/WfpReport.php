<?php

namespace App\Http\Livewire\WFP;

use App\Models\Wfp;
use Livewire\Component;

class WfpReport extends Component
{
    public $record;
    public $wfpDetails;
    public $program;
    public $balance;

    public function mount($record)
    {
        $this->record = Wfp::find($record);
        $this->wfpDetails = $this->record->wfpDetails()->get();
        // $this->program = $this->wfpDetails->sum('estimated_budget');
        $total_quantity = $this->wfpDetails->sum('total_quantity');
        $cost_per_unit = $this->wfpDetails->sum('cost_per_unit');
        $this->program = $total_quantity * $cost_per_unit;
        $this->balance = $this->record->total_allocated_fund - $this->program;
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
