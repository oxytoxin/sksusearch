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

    public $name;
    public $campus_id;

    protected function getFormSchema()
    {
        return [
            TextInput::make('name')->required(),
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
            'name' => $this->name,
            'campus_id' => $this->campus_id,
        ]);
        DB::commit();
        Notification::make()->title('Operation Success')->body('Vehicle has been added.')->success()->send();

        return redirect()->route('motorpool.vehicle.index', $vehicle);
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.motorpool.vehicle.vehicle-create');
    }
}
