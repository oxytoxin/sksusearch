<?php

namespace App\Http\Livewire;

use App\Forms\Components\SlimRepeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class TestComponent extends Component implements HasForms
{
    use InteractsWithForms;

    protected function getFormSchema(): array
    {
        return [];
    }

    public function test()
    {
    }

    public function render()
    {
        return view('livewire.test-component');
    }
}
