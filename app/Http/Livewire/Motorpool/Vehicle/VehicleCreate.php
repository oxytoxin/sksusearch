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

class VehicleCreate extends Component implements HasForms
{
    use InteractsWithForms;

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
        $vehicle = Vehicle::create([
            'model' => $this->model,
            'plate_number' => $this->plate_number,
            'campus_id' => $this->campus_id,
        ]);
        DB::commit();
        Notification::make()->title('Operation Success')->body('Vehicle has been added.')->success()->send();

        return redirect()->route('motorpool.vehicle.index', $vehicle);
    }

    public function mount($from_schedules)
    {
        if ($from_schedules == 1) {
            Notification::make()->title('Redirected')->body('No vehicles were found! You have been redirected to the add vehicle page...')->duration(4*1000)->warning()->send();
        }
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.motorpool.vehicle.vehicle-create');
    }
}
