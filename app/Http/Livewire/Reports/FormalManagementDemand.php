<?php

namespace App\Http\Livewire\Reports;

use Livewire\Component;
use App\Models\DisbursementVoucher;

class FormalManagementDemand extends Component
{
    public DisbursementVoucher $record;

    public $esign;

    public function mount(DisbursementVoucher $record)
    {
        $this->record = $record;
        $this->record->load(['cash_advance_reminder.caReminderStepHistories']);

        $histories = $this->record->cash_advance_reminder->caReminderStepHistories ?? collect();


    $type = 'FMD';

    $this->esign = $histories
        ->where('type', $type)
        ->sortByDesc('sent_at')
        ->first(); 

    }

    public function render()
    {
        return view('livewire.reports.formal-management-demand');
    }
}
