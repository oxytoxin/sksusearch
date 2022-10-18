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
    public $items = [];

    protected function getFormSchema(): array
    {
        return [
            SlimRepeater::make('items')->schema([
                TextInput::make('particulars')->required()->disableLabel(),
                TextInput::make('amount')->numeric()->required()->disableLabel(),
            ])->disableLabel()->default([])->columns(2),
        ];
    }

    public function test()
    {
        dd(1);
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.test-component');
    }
}
