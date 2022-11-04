<?php

namespace App\Http\Livewire\Requisitioner\Itinerary;

use App\Models\Itinerary;
use Filament\Notifications\Notification;
use Livewire\Component;

class ItineraryShow extends Component
{
    public Itinerary $itinerary;
    public $travel_order_id;
    public $coverage;
    public $purpose;

    public function mount()
    {
        $this->travel_order = $this->itinerary->travel_order;
        $this->coverage = $this->itinerary->coverage;
        $this->purpose = $this->itinerary->purpose != null ? $this->itinerary->purpose : '';
    }
    public function render()
    {
        return view('livewire.requisitioner.itinerary.itinerary-show');
    }

    public function save()
    {
        if ($this->purpose != null || $this->purpose != " ") {
            Itinerary::find($this->itinerary->id)->update(
                [
                    'purpose' => $this->purpose,
                ]
            );

            Notification::make()->title('Saved')->body('Purpose for this itinerary is updated')->success()->send();
        }
    }
}
