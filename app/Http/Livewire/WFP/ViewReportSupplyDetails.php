<?php

namespace App\Http\Livewire\WFP;

use App\Models\ReportedSupply;
use Livewire\Component;

class ViewReportSupplyDetails extends Component
{
    public $record;

    public function mount($record)
    {
        $this->record = ReportedSupply::find($record);
    }

    public function render()
    {
        return view('livewire.w-f-p.view-report-supply-details');
    }
}
