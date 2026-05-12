<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SignatureBlock extends Component
{
    public $signature;
    public $width;
    public $maxHeight;
    public $top;
    public $bottom;
    public $left;
    public $translateX;
    public $translateY;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $signature = null,
        $width = '10rem',
        $maxHeight = '4rem',
        $top = null,
        $bottom = null,
        $left = '50%',
        $translateX = '-50%',
        $translateY = '0',
    ) {
        $this->signature = $signature;
        $this->width = $width;
        $this->maxHeight = $maxHeight;
        $this->top = $top;
        $this->bottom = $bottom;
        $this->left = $left;
        $this->translateX = $translateX;
        $this->translateY = $translateY;
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
