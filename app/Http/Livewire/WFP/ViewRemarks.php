<?php

namespace App\Http\Livewire\WFP;

use Livewire\Component;
use App\Models\Wfp;

class ViewRemarks extends Component
{
    public $record;

    public function mount($record)
    {
        $this->record = Wfp::find($record);
    }

    public function render()
    {
        return view('livewire.w-f-p.view-remarks');
    }
}
