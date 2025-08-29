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

    public $supplementalQuarterId = null;
    public $wfpType = null;
    protected $queryString = ['supplementalQuarterId','wfpType'];

    public $history = [
        'regular_allocation' => 0,
        'less' => 0,
        'balance' => 0,
        'add' => 0,
        'total_balance' => 0,
        'description' => ''
    ];

    public $current = [
        'allocated_fund' => 0,
        'balance' => 0,
        'programmed' => 0
    ];

    public function mount($record, $isSupplemental)
    {

        $this->isSupplemental = $isSupplemental;

        if ($isSupplemental) {
            $this->record = Wfp::with(['costCenter','wfpType'])->where('id', $record)->where('supplemental_quarter_id', $this->supplementalQuarterId)->first();
            abort_unless($this->record, 404, 'Cost Center not found');

            $this->history['description'] = 'Supplemental Work and Financial Plan For Year 2025 - Q1';

            $this->allocation = $this->record->costCenter->fundAllocations->where('is_supplemental', 1)->sum('initial_amount');

            // ------------------------------------------------------------------
            $this->history['add']= number_format($this->allocation,2);
            $regular_allocation = $this->record->costCenter->fundAllocations->where('is_supplemental', 0)->sum('initial_amount');

            // dd($this->record->costCenter);

            // dd($this->record->costCenter->fundAllocations->where('is_supplemental', 0)->first());
            $this->history['regular_allocation'] = number_format( (int)$regular_allocation,2);
            // ------------------------------------------------------------------

            $this->wfpDetails = $this->record->wfpDetails()->get();
            foreach ($this->wfpDetails as $wfpDetail) {
                $this->program += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
            }

            $wfps = Wfp::with('wfpDetails')->where('wpf_type_id', $this->wfpType)->where('cost_center_id', $this->record->costCenter->id)
                    ->where(function ($query) {
                        $query->where('is_supplemental', 0)
                       ->orWhere('supplemental_quarter_id','<=',$this->supplementalQuarterId);
                         })
                    ->get();
            //old balance
            if (Wfp::where('cost_center_id', $this->record->cost_center_id)->where('is_supplemental', 0)->orWhere('supplemental_quarter_id','<',$this->supplementalQuarterId)->count() > 0) {
                $record = $wfps->filter(function($wfp){
                    return $wfp->is_supplemental === 0 || $wfp->supplemental_quarter_id < $this->supplementalQuarterId;
                });
                $allocation = $this->record->costCenter->fundAllocations->filter(function($allocation){
                    return $allocation->is_supplemental === 0 || $allocation->supplemental_quarter_id < $this->supplementalQuarterId;
                })->sum('initial_amount');
                $programmed = 0;
               foreach ($record as $item) {
                 foreach ($item->wfpDetails as $wfpDetail) {
                    $programmed += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
                }
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
                $allocation = $this->record->costCenter->fundAllocations->where('is_supplemental', 0)->sum('initial_amount');
                $programmed = 0;

                // ------------------------------------------------------------------
                 $this->history['less'] = number_format($programmed,2);
                 $this->history['balance'] = number_format($regular_allocation - $programmed,2);
                $this->history['total_balance'] = number_format($this->allocation + ($regular_allocation - $programmed),2);
            }

            $total_current_and_prev_allocation = $this->record->costCenter->fundAllocations->filter(function ($allocation){
                return $allocation->is_supplemental === 0 || $allocation->supplemental_quarter_id <= $this->supplementalQuarterId;
            })->sum('initial_amount');

            $total_current_and_prev_wfp = 0;
            foreach($wfps as $wfp){
                foreach ($wfp->wfpDetails as $wfpDetail) {
                    $total_current_and_prev_wfp += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
                }
            }
        } else {
            $this->record = Wfp::with(['costCenter','wfpType'])->where('id', $record)->where('is_supplemental', 0)->first();
            $this->history['description'] = $record ? $this->record->wfpType->description : '';
            $this->allocation = $this->record->costCenter->fundAllocations->where('wpf_type_id', $this->record->wpf_type_id)->where('is_supplemental', 0)->sum('initial_amount');
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
