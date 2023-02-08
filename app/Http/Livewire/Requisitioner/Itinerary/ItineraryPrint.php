<?php

namespace App\Http\Livewire\Requisitioner\Itinerary;

use App\Models\Itinerary;
use Carbon\Carbon;
use Livewire\Component;

class ItineraryPrint extends Component
{
    public Itinerary $itinerary;
    public $travel_order;
    public $coverage;
    public $immediate_signatory;

    public function mount()
    {
        $this->travel_order = $this->itinerary->travel_order;
        $this->coverage = $this->itinerary->coverage;
        $this->itinerary->load('user.employee_information');
        $this->immediate_signatory = $this->itinerary->travel_order->signatories()->with('employee_information')->first();
    }

    public function render()
    {
        return view('livewire.requisitioner.itinerary.itinerary-print');
    }
}
