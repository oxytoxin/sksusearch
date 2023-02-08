<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\EmployeeInformation;
use App\Models\TravelOrder;
use Livewire\Component;

class TravelOrdersShow extends Component
{
    public TravelOrder $travel_order;

    public function render()
    {
        return view('livewire.requisitioner.travel-orders.travel-orders-show');
    }
}
