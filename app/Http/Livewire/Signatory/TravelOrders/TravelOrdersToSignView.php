<?php

namespace App\Http\Livewire\Signatory\TravelOrders;

use App\Models\TravelOrder;
use Livewire\Component;

class TravelOrdersToSignView extends Component
{
    public TravelOrder $travel_order;

    public $limit = 5;

    public function render()
    {
        return view('livewire.signatory.travel-orders.travel-orders-to-sign-view');
    }

    public function showMore(){
        $this->limit+=5;    
    }
}
