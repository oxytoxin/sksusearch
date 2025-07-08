<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\FundAllocation;
use App\Models\WfpDetail;
use DB;


class PreExport164T implements FromView
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

    public $is_q1 = false;
    public $activeButton;

    public $non_supplemental_fund_allocation;
    public $forwarded_ppmp_details;

    public $non_supplemental_total_programmed;

    public $showPre = false;

    public function __construct($selectedType, $fund_allocation, $ppmp_details, $total_allocated, $total_programmed, $balance,$non_supplemental_fund_allocation, $forwarded_ppmp_details,$non_supplemental_total_programmed, $is_q1 = false, $activeButton)
    {
        $this->fund_allocation = $fund_allocation;
        $this->ppmp_details = $ppmp_details;
        $this->total_allocated = $total_allocated;
        $this->total_programmed = $total_programmed;
        $this->balance = $balance;
        $this->selectedType = $selectedType;
        $this->is_q1 = $is_q1;
        $this->activeButton = $activeButton;
        $this->non_supplemental_fund_allocation = $non_supplemental_fund_allocation;
        $this->forwarded_ppmp_details = $forwarded_ppmp_details;
        $this->non_supplemental_total_programmed = $non_supplemental_total_programmed;
    }

    public function view(): View
    {

        return view('exports.164T', [
            'fund_allocation' => $this->fund_allocation,
            'ppmp_details' => $this->ppmp_details,
            'total_allocated' => $this->total_allocated,
            'total_programmed' => $this->total_programmed,
            'balance' => $this->balance,
            'selectedType' => $this->selectedType,
            'activeButton' => $this->activeButton,
            'is_q1' => $this->is_q1,
            'non_supplemental_fund_allocation' => $this->non_supplemental_fund_allocation,
            'forwarded_ppmp_details' => $this->forwarded_ppmp_details,
            'showPre' => $this->showPre,
            'non_supplemental_total_programmed' => $this->non_supplemental_total_programmed
        ]);
    }
}
