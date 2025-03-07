<?php

namespace App\Http\Livewire\Motorpool\Requests;

use Livewire\Component;
use App\Models\RequestSchedule;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Campus;

class FuelRequisition extends Component implements HasForms
{
    use InteractsWithForms;

    public $request;
    public $showPrintable = false;
    public $selectedDate;

    public function mount($request)
    {
        $this->request = RequestSchedule::find($request);
    }


    protected function getFormSchema()
    {
        return [
            TextInput::make('model')->required(),
            TextInput::make('plate_number')->required(),
            Select::make('campus_id')
                ->label('Campus')
                ->options(Campus::pluck('name', 'id'))
                ->required()
        ];
    }

    public function render()
    {
        return view('livewire.motorpool.requests.fuel-requisition');
    }
}
