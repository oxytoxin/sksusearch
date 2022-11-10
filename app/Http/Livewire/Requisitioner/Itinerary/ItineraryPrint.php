<?php

namespace App\Http\Livewire\Requisitioner\Itinerary;

use App\Models\Itinerary;
use Carbon\Carbon;
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
        
        // dd($this->itinerary->itinerary_entries->where('place','==','Home')->get);
        return view('livewire.requisitioner.itinerary.itinerary-print');
    }
}
