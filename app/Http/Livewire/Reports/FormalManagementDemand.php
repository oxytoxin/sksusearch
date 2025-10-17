<?php

namespace App\Http\Livewire\Reports;

use Livewire\Component;
use App\Models\DisbursementVoucher;

class FormalManagementDemand extends Component
{
    public DisbursementVoucher $record;

    public function mount(DisbursementVoucher $record)
    {
        $this->record = $record;
        $this->record->load(['cash_advance_reminder']);
    }

    public function render()
    {
        return view('livewire.reports.formal-management-demand');
    }
}
