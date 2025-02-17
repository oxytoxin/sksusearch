<?php

namespace App\Http\Livewire\Motorpool\Requests;

use Livewire\Component;
use App\Models\RequestSchedule;
use App\Models\RequestScheduleTimeAndDate;

class RequestShow extends Component
{
    public $request;
    public $showPrintable = false;
    public $selectedDate;

    public function mount($request)
    {
        $this->request = RequestSchedule::find($request);
    }

    public function showPrintable($id)
    {
        $request = RequestScheduleTimeAndDate::find($id);
        $this->selectedDate = $request->travel_date;
        $this->showPrintable = true;
    }
    public function render()
    {
        return view('livewire.motorpool.requests.request-show');
    }
}
