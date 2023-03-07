<?php

namespace App\Http\Livewire\LiquidationReports;

use App\Models\LiquidationReport;
use Livewire\Component;

class LiquidationReportsShow extends Component
{
    public LiquidationReport $liquidation_report;

    public function mount()
    {
        $this->liquidation_report->load(['disbursement_voucher.disbursement_voucher_particulars']);
        $this->liquidation_report->disbursement_voucher->loadSum('disbursement_voucher_particulars as total_amount', 'final_amount');
    }

    public function render()
    {
        return view('livewire.liquidation-reports.liquidation-reports-show', [
            'to_reimburse' => collect($this->liquidation_report->particulars)->sum('amount') - $this->liquidation_report->disbursement_voucher->total_amount,
        ]);
    }
}
