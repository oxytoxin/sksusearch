<?php

namespace App\Http\Livewire\Requisitioner\Itenerary;

use App\Models\Itenerary;
use Livewire\Component;

class IteneraryShow extends Component
{
    public Itenerary $itenerary;
    public $travel_order_id;
    public $coverage;

    public function mount(){
        $this->travel_order = $this->itenerary->travel_order;
        $this->coverage = $this->itenerary->coverage;

    }
    public function render()
    {
        
        return view('livewire.requisitioner.itenerary.itenerary-show');
    }
}
