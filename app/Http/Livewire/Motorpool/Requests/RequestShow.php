<?php

namespace App\Http\Livewire\Motorpool\Requests;

use App\Models\RequestSchedule;
use Livewire\Component;

class RequestShow extends Component
{
    public $request;

    public function mount($request)
    {
        $this->request = RequestSchedule::find($request);
    }
    public function render()
    {
        return view('livewire.motorpool.requests.request-show');
    }
}
