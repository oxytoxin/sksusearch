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

    public $history = [
        'regular_allocation' => 0,
        'less' => 0,
        'balance' => 0,
        'add' => 0,
        'total_balance' => 0
    ];

    public function mount($record, $isSupplemental)
    {

        $this->isSupplemental = $isSupplemental;

        if ($isSupplemental) {
            $this->record = Wfp::where('id', $record)->where('is_supplemental', 1)->first();
            $this->allocation = $this->record->costCenter->fundAllocations->where('is_supplemental', 1)->sum('initial_amount');

            // ------------------------------------------------------------------
            $this->history['add']= number_format($this->allocation,2);
            $regular_allocation = $this->record->costCenter->fundAllocations->where('is_supplemental', 0)->first()?->initial_amount ?? 0;
            $this->history['regular_allocation'] = number_format( (int)$regular_allocation,2);
            // ------------------------------------------------------------------

            $this->wfpDetails = $this->record->wfpDetails()->get();
            foreach ($this->wfpDetails as $wfpDetail) {
                $this->program += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
            }
            //old balance
            if ($this->record->costCenter->wfp->where('is_supplemental', 0)->count() > 0) {
                $record = Wfp::where('cost_center_id', $this->record->costCenter->id)
                    ->where('is_supplemental', 0)
                    ->first();
                $allocation = $this->record->costCenter->fundAllocations->where('is_supplemental', 0)->sum('initial_amount');
                $wfpDetails = $record->wfpDetails()->get();
                $programmed = 0;
                foreach ($wfpDetails as $wfpDetail) {
                    $programmed += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
                }
                // ------------------------------------------------------------------
                 $this->history['less'] = number_format($programmed,2);
                 $this->history['balance'] = number_format($regular_allocation - $programmed,2);
                $this->history['total_balance'] = number_format($this->allocation + ($regular_allocation - $programmed),2);

                 // ------------------------------------------------------------------

                $this->balance = $allocation - $programmed;
                //$this->balance = $this->record->costCenter->fundAllocations->where('is_supplemental', 1)->sum('initial_amount') - $this->program;
            } else {
                $this->balance = $this->record->costCenter->fundAllocations->where('is_supplemental', 1)->sum('initial_amount') - $this->program;
            }
        } else {
            $this->record = Wfp::where('id', $record)->where('is_supplemental', 0)->first();
            $this->allocation = $this->record->costCenter->fundAllocations->where('is_supplemental', 0)->sum('initial_amount');
            $this->wfpDetails = $this->record->wfpDetails()->get();
            foreach ($this->wfpDetails as $wfpDetail) {
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
