<?php

namespace App\Http\Livewire\Requisitioner\Motorpool;

use App\Models\RequestSchedule;
use Livewire\Component;

class VehicleRequestFormShow extends Component
{
    public $request;

    public function mount($request)
    {
        $this->request = RequestSchedule::find($request);
    }

    public function render()
    {
        return view('livewire.requisitioner.motorpool.vehicle-request-form-show');
    }
}
