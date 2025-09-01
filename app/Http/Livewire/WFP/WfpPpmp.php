<?php

namespace App\Http\Livewire\WFP;

use App\Models\FundAllocation;
use App\Models\SupplementalQuarter;
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

    public $supplementalQuarterId = null;
    public $wfpType = null;

    public $costCenterId = null;
    protected $queryString = ['supplementalQuarterId', 'wfpType', 'costCenterId'];

    public $fundAllocations = [];

    public $supplementalQuarter = null;

    public $oldRecords = [];
    public $current = [
        'regular_allocation' => 0,
        'regular_programmed' => 0,
        'balance' => 0,
    ];
     public $history = [
        'regular_allocation' => 0,
        'regular_programmed' => 0,
        'balance' => 0,
    ];


    public function mount($record, $isSupplemental)
    {
        if($isSupplemental) {
            $this->supplementalQuarter = SupplementalQuarter::where('id', $this->supplementalQuarterId)->first();
        }
        $this->fundAllocations = FundAllocation::where('cost_center_id', $this->costCenterId)->where('wpf_type_id', $this->wfpType)->where(function($query) use ($isSupplemental){
                if($isSupplemental){
                     $query->where('is_supplemental',0)->orWhere(function($q){
                    $q->whereNotNull('supplemental_quarter_id')
                       ->where('supplemental_quarter_id', '<=', $this->supplementalQuarterId);
                 });
                }else{
                    $query->where('is_supplemental',0);
                }
            })->get();

        $this->current['regular_allocation'] = $this->fundAllocations->sum('initial_amount');

        $workFinancialPlans = Wfp::with(['wfpDetails'])
            ->where('cost_center_id', $this->costCenterId)->where(function ($query) use ($record, $isSupplemental) {
                 if($isSupplemental){
                    $query->where('is_supplemental',0)->orWhere(function($q){
                        $q->whereNotNull('supplemental_quarter_id')
                        ->where('supplemental_quarter_id', '<=', $this->supplementalQuarterId);
                    });
                 }else{
                    $query->where('is_supplemental',0);
                 }
            })->get();

        $this->record = $workFinancialPlans->where('id', $record)->first();

       if($isSupplemental){
            $this->oldRecords = $workFinancialPlans->filter(function ($workFinancialPlan) use ($record) {
                return $workFinancialPlan->is_supplemental === 0 || ($workFinancialPlan->supplemental_quarter_id < $this->supplementalQuarterId && $workFinancialPlan->supplemental_quarter_id !== null);
            });
       }


        $this->wfpDetails = $this->record->wfpDetails->where('is_ppmp',1);

        foreach ($this->wfpDetails as $wfpDetail) {
            // PPMP ONLY
            $this->current['regular_programmed'] += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
        }

        foreach($this->oldRecords as $oldRecord) {
            foreach ($oldRecord->wfpDetails as $wfpDetail) {
                $this->history['regular_programmed'] += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
            }
        }

        $proc_programmed = 0;

        if (count($this->oldRecords) > 0) {

            $this->procurements = $this->record->wfpDetails->filter(function($wfpDetail) {
                return in_array($wfpDetail->supply->category_item_budget_id, self::PROCUREMENT_IDS);
            });
            if (count($this->procurements) > 0) {
                foreach ($this->procurements as $procurement) {
                    $proc_programmed += $procurement->total_quantity * $procurement->cost_per_unit;
                }
                $this->program = $this->program + $proc_programmed;
            }
        }
        $this->balance = $this->total_allocated - $proc_programmed ;
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
