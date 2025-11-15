<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SignatureBlock extends Component
{

    public $name ;
    public $position;
    public $signature ;
    public $offsetY;
    public $offsetX ;
    public $size;
    public $gap;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name,
        $signature,
        $position = null,
        $offsetY = '-1.2rem',
        $offsetX = '0rem',
        $size = '6rem',
        $gap = '1rem',
    ) {
        $this->name = $name;
        $this->position = $position;
        $this->signature = $signature;
        $this->offsetY = $offsetY;
        $this->offsetX = $offsetX;
        $this->size = $size;
        $this->gap = $gap;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.signature-block');
    }
}
