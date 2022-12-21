<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\TravelOrder;
use Livewire\Component;

class TravelOrdersView extends Component
{
    public TravelOrder $travel_order;
    
    public $limit = 2;

    public function render()
    {
        return view('livewire.requisitioner.travel-orders.travel-orders-view');
    }
    public function showMore()
    {
        $this->limit += 5;
    }
}
