<?php

namespace App\Http\Livewire\Offices;

use App\Models\BatchTransmittal;
use Livewire\Component;

class BatchTransmittalShow extends Component
{
    public BatchTransmittal $batch;

    public function mount(BatchTransmittal $batch)
    {
        $this->batch = $batch->load([
            'items.disbursement_voucher.disbursement_voucher_particulars',
            'items.disbursement_voucher.current_step',
            'items.liquidation_report.disbursement_voucher',
            'items.liquidation_report.requisitioner.employee_information',
            'items.liquidation_report.current_step',
            'created_by_user.employee_information',
            'forwarded_by_user.employee_information',
            'received_by_user.employee_information',
        ]);
    }

    public function render()
    {
        return view('livewire.offices.batch-transmittal-show');
    }
}
