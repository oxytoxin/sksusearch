<?php

namespace App\Http\Livewire\Requisitioner\Itinerary;

use App\Models\Itinerary;
use Livewire\Component;

class ItineraryPrint extends Component
{
    use PreparesItineraryOfficialForm;

    public Itinerary $itinerary;
    public $travel_order;
    public $coverage;

    public function mount()
    {
        $this->itinerary->load([
            'itinerary_entries.mot',
            'user.employee_information.position',
            'user.employee_information.office',
            'user.signature',
            'travel_order.disbursement_vouchers.fund_cluster',
            'travel_order.signatories.employee_information.position',
            'travel_order.signatories.employee_information.office',
            'travel_order.signatories.signature',
        ]);
        $this->travel_order = $this->itinerary->travel_order;
        $this->coverage = $this->itinerary->coverage;
    }

    public function render()
    {
        return view('livewire.requisitioner.itinerary.itinerary-print', [
            'itineraryForm' => $this->itineraryFormData(),
        ]);
    }
}
