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

    public function __construct($selectedType, $fund_allocation, $ppmp_details, $total_allocated, $total_programmed, $balance)
    {
        $this->fund_allocation = $fund_allocation;
        $this->ppmp_details = $ppmp_details;
        $this->total_allocated = $total_allocated;
        $this->total_programmed = $total_programmed;
        $this->balance = $balance;
        $this->selectedType = $selectedType;
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
        ]);
    }
}
