<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EsignatureBlock extends Component
{
    public $signature;
    public $signedBy;
    public $signedAt;
    public $width;
    public $maxHeight;
    public $top;
    public $bottom;
    public $left;
    public $translateX;
    public $translateY;
    public $infoClass;
    public $infoOffsetX;
    public $infoOffsetY;
    public $showInfo;

    public function __construct(
        $signature = null,
        $signedBy = null,
        $signedAt = null,
        $width = '10rem',
        $maxHeight = '4rem',
        $top = null,
        $bottom = null,
        $left = '50%',
        $translateX = '-50%',
        $translateY = '0',
        $infoClass = 'text-[9px] text-left leading-tight',
        $infoOffsetX = '62%',
        $infoOffsetY = '0',
        $showInfo = false,
    ) {
        $this->signature = $signature;
        $this->signedBy = $signedBy;
        $this->signedAt = $signedAt;
        $this->width = $width;
        $this->maxHeight = $maxHeight;
        $this->top = $top;
        $this->bottom = $bottom;
        $this->left = $left;
        $this->translateX = $translateX;
        $this->translateY = $translateY;
        $this->infoClass = $infoClass;
        $this->infoOffsetX = $infoOffsetX;
        $this->infoOffsetY = $infoOffsetY;
        $this->showInfo = $showInfo;
    }

    public function render()
    {
        return view('components.esignature-block');
    }
}
