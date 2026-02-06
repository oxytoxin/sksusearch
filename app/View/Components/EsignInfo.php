<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EsignInfo extends Component
{
public $name;
    public $datetime;
    public $label;
    public $textclass;
    public $offsetX;
    public $offsetY;

    public function __construct(
        $name = null,
        $datetime = null,
        $label = 'Electronically signed by:',
        $textclass = 'text-xs text-gray-700 leading-tight',
        $offsetX = '0rem',
        $offsetY = '0rem',
    ) {
        $this->name = $name;
        $this->datetime = $datetime;
        $this->label = $label;
        $this->textclass = $textclass;
        $this->offsetX = $offsetX;
        $this->offsetY = $offsetY;
    }

    public function render()
    {
        return view('components.esign-info');
    }
}
