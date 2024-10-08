<?php

namespace App\Http\Livewire\WFP;

use Filament\Forms\Components\Grid;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;

class RequestSupply extends Component implements HasForms
{
    use InteractsWithForms;

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('particulars')->label('Particular')->required(),
                TextInput::make('unit_cost')
                  ->mask(fn (TextInput\Mask $mask) => $mask
                    ->numeric()
                    ->thousandsSeparator(','))
                  ->required(),
                  Radio::make('is_ppmp')
                  ->label('Is this PPMP?')
                  ->options([
                      1 => 'Yes',
                      0 => 'No',
                  ])->inline(),
            ]),

        ];
    }

    public function render()
    {
        return view('livewire.w-f-p.request-supply');
    }
}
