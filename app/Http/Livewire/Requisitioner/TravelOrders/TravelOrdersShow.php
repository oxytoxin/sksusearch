<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\EmployeeInformation;
use App\Models\TravelOrder;
use Livewire\Component;

class TravelOrdersShow extends Component
{
    public TravelOrder $travel_order;

    public function mount()
    {
        $this->travel_order->load([
            'immediate_supervisors.signature',
            'immediate_supervisors.employee_information',
            'recommending_approval.signature',
            'recommending_approval.employee_information',
            'university_president.signature',
            'university_president.employee_information',
        ]);
    }

    public function render()
    {
        return view('livewire.requisitioner.travel-orders.travel-orders-show');
    }
}
