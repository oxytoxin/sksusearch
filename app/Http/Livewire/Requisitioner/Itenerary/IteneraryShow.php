<?php

namespace App\Http\Livewire\Requisitioner\Itenerary;

use App\Models\Itenerary;
use Livewire\Component;

class IteneraryShow extends Component
{
    public Itenerary $itenerary;

    public function render()
    {
        return view('livewire.requisitioner.itenerary.itenerary-show');
    }
}
