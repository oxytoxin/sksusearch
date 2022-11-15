<?php

namespace App\Http\Livewire\Motorpool\Vehicle;

use App\Models\Campus;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;

class VehicleEdit extends Component implements HasForms
{
    use InteractsWithForms;

    public $vehicle;
    public $model;
    public $plate_number;
    public $campus_id;

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

    public function save()
    {
        $this->validate();
        DB::beginTransaction();

        $this->vehicle->model = $this->model;
        $this->vehicle->plate_number = $this->plate_number;
        $this->vehicle->campus_id = $this->campus_id;

        $this->vehicle->save();
        DB::commit();
        Notification::make()->title('Operation Success')->body('Vehicle has been updated.')->success()->send();

        return redirect()->route('motorpool.vehicle.index');
    }

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
        $this->form->fill([
            'name' => $vehicle->name,
            'plate_number' => $vehicle->plate_number,
            'campus_id' => $vehicle->campus_id,
        ]);
    }

    public function render()
    {
        return view('livewire.motorpool.vehicle.vehicle-edit');
    }
}
