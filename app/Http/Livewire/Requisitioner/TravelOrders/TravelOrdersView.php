<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\TravelOrder;
use Livewire\Component;

class TravelOrdersView extends Component
{
    public TravelOrder $travel_order;

    public $limit = 3;

    public function mount()
    {
        $this->travel_order->load('sidenotes.user.employee_information');
    }

    public function render()
    {
        return view('livewire.requisitioner.travel-orders.travel-orders-view', [
            'sidenotes' => $this->travel_order->sidenotes()->limit($this->limit)->with('user.employee_information')->get(),
        ]);
    }
    public function showMore()
    {
        $this->limit += 5;
    }
}
