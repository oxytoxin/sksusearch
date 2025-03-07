<?php

namespace App\Http\Livewire\Motorpool\Requests;

use Livewire\Component;
use App\Models\RequestSchedule;

class FuelRequisition extends Component
{
    public $request;
    public $showPrintable = false;
    public $selectedDate;

    public function mount($request)
    {
        $this->request = RequestSchedule::find($request);
    }

    public function render()
    {
        return view('livewire.motorpool.requests.fuel-requisition');
    }
}
