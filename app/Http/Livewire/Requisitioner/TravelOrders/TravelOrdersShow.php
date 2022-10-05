<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\EmployeeInformation;
use App\Models\TravelOrder;
use Livewire\Component;

class TravelOrdersShow extends Component
{
    public TravelOrder $travel_order;

    public $applicant_ids = array();
    public $applicants;
    public $signatory_ids = array();
    public $signatories;
    public function render()
    {

        foreach ($this->travel_order->applicants as $applicant) {
            $this->applicant_ids[] = $applicant->id;
        }
        foreach ($this->travel_order->signatories as $signatory) {
            $this->signatory_ids[] = $signatory->id;
        }

        $this->applicants = EmployeeInformation::whereIn('id',  $this->applicant_ids)->get();
        $signatories = EmployeeInformation::whereIn('id',  $this->signatory_ids)->get();
        $this->signatories = $signatories->reverse();

        return view('livewire.requisitioner.travel-orders.travel-orders-show');
    }
}
