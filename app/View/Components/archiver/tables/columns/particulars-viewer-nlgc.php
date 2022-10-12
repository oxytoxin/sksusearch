<?php

namespace App\View\Components\archiver\tables\columns;

use Illuminate\View\Component;

class particulars-viewer-nlgc extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.archiver.tables.columns.particulars-viewer-nlgc');
    }
}
