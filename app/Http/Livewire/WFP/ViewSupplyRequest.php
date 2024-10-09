<?php

namespace App\Http\Livewire\WFP;

use App\Models\WfpRequestedSupply;
use Livewire\Component;

class ViewSupplyRequest extends Component
{
    public $record;

    public function mount($record)
    {
        $this->record = WfpRequestedSupply::find($record);
    }

    public function render()
    {
        return view('livewire.w-f-p.view-supply-request');
    }
}
