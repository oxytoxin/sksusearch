<?php

namespace App\Http\Livewire\LiquidationReports;

use App\Models\LiquidationReport;
use Livewire\Component;

class LiquidationReportsShow extends Component
{
    public LiquidationReport $liquidation_report;

    public function mount()
    {
        $this->liquidation_report->load([
            'disbursement_voucher.disbursement_voucher_particulars',
            'requisitioner.signature',
            'requisitioner.employee_information',
            'signatory.signature',
            'signatory.employee_information',
        ]);
        $this->liquidation_report->disbursement_voucher->loadSum('disbursement_voucher_particulars as total_amount', 'final_amount');
    }

    public function render()
    {
        $accountant = \App\Models\EmployeeInformation::with('user.signature')
            ->where('position_id', 15)
            ->where('office_id', 3)
            ->first();

        return view('livewire.liquidation-reports.liquidation-reports-show', [
            'to_reimburse' => collect($this->liquidation_report->particulars)->sum('amount') - $this->liquidation_report->disbursement_voucher->total_amount,
            'accountant' => $accountant,
        ]);
    }
}
