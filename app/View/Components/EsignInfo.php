<?php

    namespace App\View\Components;

    use Carbon\Carbon;
    use Illuminate\View\Component;

    class EsignInfo extends Component
    {
        public $name;
        public $datetime;
        public $label;
        public $textclass;
        public $offsetX;
        public $offsetY;
        public $timezone;
        public $format;
        public $showDescription;

        public function __construct(
            $name = null,
            $datetime = null,
            $label = 'Electronically signed by:',
            $textclass = 'text-xs text-gray-700 leading-tight',
            $offsetX = '0rem',
            $offsetY = '0rem',
            $timezone = 'Asia/Manila',
            $format = 'm/d/Y, g:i A',
            $showDescription = false,
        ) {
            $this->name = $name;
            $this->datetime = $datetime ? Carbon::parse($datetime)->timezone($timezone)->format($format) : null;
            $this->label = $label;
            $this->textclass = $textclass;
            $this->offsetX = $offsetX;
            $this->offsetY = $offsetY;
            $this->timezone = $timezone;
            $this->format = $format;
            $this->showDescription = $showDescription;
        }

        public function render()
        {
            return view('components.esign-info');
        }
    }
