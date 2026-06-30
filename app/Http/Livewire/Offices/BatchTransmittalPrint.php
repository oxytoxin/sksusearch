<?php

namespace App\Http\Livewire\Offices;

use App\Models\BatchTransmittal;
use Livewire\Component;

class BatchTransmittalPrint extends Component
{
    public BatchTransmittal $batch;

    public function mount(BatchTransmittal $batch)
    {
        $this->batch = $batch->load([
            'items.disbursement_voucher.disbursement_voucher_particulars',
            'items.liquidation_report.disbursement_voucher',
            'items.liquidation_report.requisitioner.employee_information',
            'created_by_user.employee_information',
        ]);
    }

    public function render()
    {
        return view('livewire.offices.batch-transmittal-print');
    }
}
