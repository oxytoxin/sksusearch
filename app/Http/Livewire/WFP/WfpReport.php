<?php

namespace App\Http\Livewire\WFP;

use App\Models\FundAllocation;
use App\Models\SupplementalQuarter;
use App\Models\Wfp;
use App\Models\WpfType;
use Illuminate\Support\Facades\DB;
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

    public $costCenterId = null;

    public $is164 = null;

    protected $queryString = ['supplementalQuarterId', 'wfpType', 'costCenterId', 'is164'];
    public $oldRecords = [];
    public $history = [
        'regular_allocation' => 0,
        'regular_programmed' => 0,
        'less' => 0,
        'balance' => 0,
        'add' => 0,
        'total_balance' => 0,
        'description' => '',
    ];

    public $current = [
        'regular_allocation' => 0,
        'total_allocation' => 0,
        'balance' => 0,
        'regular_programmed' => 0,
    ];

    public $draftItems = [];

    public $currentSupplementalQuarter = null;
    public $prevSupplementalQuarter = null;
    public function mount($record, $isSupplemental)
    {
        $this->isSupplemental = $isSupplemental;
        $wfpType = WpfType::find($this->wfpType);
        $this->currentSupplementalQuarter = SupplementalQuarter::find($this->supplementalQuarterId);

        if ($this->supplementalQuarterId > 1) {
            $this->prevSupplementalQuarter = SupplementalQuarter::find($this->supplementalQuarterId - 1);
        }
        // WFP SHOULD EXIST
        abort_unless(!empty($wfpType), 404);

        $fundAllocationCategoryIds = FundAllocation::where('cost_center_id', $this->costCenterId)->where('wpf_type_id', $this->wfpType)
            ->where('supplemental_quarter_id', $this->supplementalQuarterId)
            ->pluck('category_group_id')->toArray();
        // RETRIEVE OLD AND CURRENT WFP
        $workFinalcialPlans = [];
        if ($isSupplemental) {
            $workFinalcialPlans = Wfp::with(['costCenter' => [
                'fundAllocations' => function ($query) {
                    $query->where('wpf_type_id', $this->wfpType)
                        ->where(function ($q) {
                            $q->where('is_supplemental', 0)
                                ->orWhere(function ($query) {
                                    $query->where('supplemental_quarter_id', '!=', null)
                                        ->where('supplemental_quarter_id', '<=', $this->supplementalQuarterId);
                                });
                        });
                }
            ], 'wfpType', 'wfpDetails' => function ($query) use ($fundAllocationCategoryIds) {
                $query->when(is_null($this->is164), function ($query) use ($fundAllocationCategoryIds) {
                    $query->whereIn('category_group_id', $fundAllocationCategoryIds);
                });
            }])
                ->where('wpf_type_id', $this->wfpType)
                ->where('cost_center_id', $this->costCenterId)
                ->where(function ($q) {
                    $q->where('is_supplemental', 0)
                        ->orWhere(function ($query) {
                            $query->where('supplemental_quarter_id', '!=', null)->where('supplemental_quarter_id', '<=', $this->supplementalQuarterId);
                        });
                })->get();
        } else {
            $workFinalcialPlans = Wfp::with(['costCenter' => [
                'fundAllocations' => function ($query) {
                    $query->where('wpf_type_id', $this->wfpType)->where(function ($q) {
                        $q->where('is_supplemental', 0);
                    });
                }
            ], 'wfpType', 'wfpDetails'])
                ->whereKey($record)
                ->where('is_supplemental', 0)->get();
        }


        $this->record = $workFinalcialPlans->where('id', $record)->first();
        if (!$isSupplemental) {
            $supplyIds = $this->record->wfpDetails->pluck('supply_id')->toArray();
            $this->draftItems = DB::table('fund_draft_items')
                ->join('fund_drafts', 'fund_draft_items.fund_draft_id', '=', 'fund_drafts.id')
                ->where('fund_drafts.fund_allocation_id', $this->record->costCenter->fundAllocations->first()->id)
                ->whereNotIn('particular_id', $supplyIds)
                ->get();

        }


        $this->oldRecords = $workFinalcialPlans->filter(function ($wfp) use ($record) {
            return ($wfp->supplemental_quarter_id < $this->supplementalQuarterId && $wfp->supplemental_quarter_id !== null) || $wfp->is_supplemental === 0;
        });


        // HISTORY
        $this->history['description'] = "{$wfpType->description}";
        $regular_allocation = $this->record->costCenter->fundAllocations->filter(function ($allocation) use ($record) {
            return $allocation->is_supplemental === 0 || ($allocation->supplemental_quarter_id < $this->supplementalQuarterId && $allocation->supplemental_quarter_id !== null);
        })->sum('initial_amount');
        $this->history['regular_allocation'] = $regular_allocation;
        $this->history['add'] = number_format($this->allocation, 2);

        // ------------------------------------------------------------------
        // CUTTENT
        $this->allocation = $this->record->costCenter->fundAllocations->where('supplemental_quarter_id', $this->supplementalQuarterId)->where('is_supplemental', 1)->sum('initial_amount');
        $this->current['regular_allocation'] = $this->allocation;
        // ------------------------------------------------------------------

        $this->wfpDetails = $this->record->wfpDetails;
        foreach ($this->wfpDetails as $wfpDetail) {
            $this->program += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
        }

        if(count($this->draftItems) > 0) {
            foreach ($this->draftItems as $draftItem) {
                $this->program += (int)$draftItem->total_quantity * (int)$draftItem->cost_per_unit;
            }
        }

       $this->current['regular_programmed'] = $this->program;


        //HISTORY
        $programmed = 0;
        if ($isSupplemental) {
            foreach ($this->oldRecords as $item) {
                foreach ($item->wfpDetails as $wfpDetail) {
                    $this->history['regular_programmed'] += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
                }
            }
        }
        // ------------------------------------------------------------------
        $this->history['less'] = $programmed;
        $this->history['balance'] = $this->history['regular_allocation'] - $this->history['regular_programmed'];
        $this->history['total_balance'] = number_format($this->allocation + ($regular_allocation - $programmed), 2);

        // ------------------------------------------------------------------
        $this->balance = $this->oldRecords->sum('initial_amount') - $programmed;
        $this->current['total_allocation'] =  $this->current['regular_allocation'] + $this->history['balance'];
        $this->current['balance'] =  $this->current['total_allocation'] - $this->current['regular_programmed'];



        $total_current_and_prev_wfp = 0;
        foreach ($workFinalcialPlans as $wfp) {
            foreach ($wfp->wfpDetails as $wfpDetail) {
                $total_current_and_prev_wfp += $wfpDetail->total_quantity * $wfpDetail->cost_per_unit;
            }
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
