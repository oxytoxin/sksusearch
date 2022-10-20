<?php

namespace App\Http\Livewire\Requisitioner\Itinerary;

use App\Models\Itinerary;
use Livewire\Component;

class ItineraryPrint extends Component
{
    public Itinerary $itinerary;
    public $travel_order_id;
    public $coverage;

    public function mount()
    {
        $this->travel_order = $this->itinerary->travel_order;
        $this->coverage = $this->itinerary->coverage;
    }

    public function render()
    {
        return view('livewire.requisitioner.itinerary.itinerary-print');
    }
}
