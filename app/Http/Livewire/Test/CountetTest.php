<?php

namespace App\Http\Livewire\Test;

use Livewire\Component;

class CountetTest extends Component
{

    public $count = 0;


    //lister
    protected $listeners = ['incrementCounter' => 'increment'];
    public function increment()
    {
        $this->count++;
    }
    public function render()
    {
        return view('livewire.test.countet-test');
    }
}
