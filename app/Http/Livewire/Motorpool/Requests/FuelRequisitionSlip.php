<?php

namespace App\Http\Livewire\Motorpool\Requests;

use App\Models\FuelRequisition;
use Livewire\Component;

class FuelRequisitionSlip extends Component
{
    public $request;

    public function mount($request)
    {
        $this->request = FuelRequisition::find($request);
    }

    public function render()
    {
        return view('livewire.motorpool.requests.fuel-requisition-slip');
    }
}
