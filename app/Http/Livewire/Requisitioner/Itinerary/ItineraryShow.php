<?php

namespace App\Http\Livewire\Requisitioner\Itinerary;

use App\Models\Itinerary;
use Filament\Notifications\Notification;
use Livewire\Component;

class ItineraryShow extends Component
{
    public Itinerary $itinerary;
    public $travel_order;
    public $coverage;
    public $purpose;
    public $print_route;
    public $is_requisitioner;

    public function mount()
    {
        $this->travel_order = $this->itinerary->travel_order;
        $this->coverage = $this->itinerary->coverage;
        $this->purpose = $this->itinerary->purpose != null ? $this->itinerary->purpose : '';
        $this->is_requisitioner = str_replace('/', '', request()->route()->getPrefix()) == 'requisitioner';
        $this->print_route = route(str_replace('/', '', request()->route()->getPrefix()) . '.itinerary.print', ['itinerary' => $this->itinerary]);
    }
    public function render()
    {
        return view('livewire.requisitioner.itinerary.itinerary-show');
    }

    public function save()
    {
        if (filled($this->purpose)) {
            $this->itinerary->update([
                'purpose' => $this->purpose,
            ]);
            Notification::make()->title('Saved')->body('Purpose for this itinerary is updated')->success()->send();
        }
    }
}
