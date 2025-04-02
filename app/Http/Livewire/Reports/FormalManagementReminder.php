<?php

namespace App\Http\Livewire\Reports;

use Livewire\Component;
use App\Models\DisbursementVoucher;

class FormalManagementReminder extends Component
{
    public DisbursementVoucher $record;

    public function mount(DisbursementVoucher $record)
    {
        $this->record = $record;
    }

    public function render()
    {
        return view('livewire.reports.formal-management-reminder');
    }
}
