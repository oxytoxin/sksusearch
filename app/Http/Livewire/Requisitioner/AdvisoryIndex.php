<?php

namespace App\Http\Livewire\Requisitioner;

use App\Models\Advisory;
use Livewire\Component;

class AdvisoryIndex extends Component
{
    public function render()
    {
        return view('livewire.requisitioner.advisory-index', [
            'advisories' => Advisory::orderByDesc('published_at')->orderByDesc('id')->get(),
        ]);
    }
}
