<?php

namespace App\Http\Livewire\Motorpool\Vehicle;

use App\Models\Vehicle;
use Filament\Tables;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\Action;
use Livewire\Component;

class VehicleIndex extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected function getTableQuery()
    {
        return Vehicle::query();
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('model')
                ->searchable(),
            Tables\Columns\TextColumn::make('plate_number')
                ->searchable(),
            Tables\Columns\TextColumn::make('campus.name')
                ->label('Campus')
                ->sortable()
                ->searchable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('edit')
                ->icon('heroicon-s-pencil')
                ->url(fn (Vehicle $record): string => route('motorpool.vehicle.edit', $record))
        ];
    }

    public function render()
    {
        return view('livewire.motorpool.vehicle.vehicle-index');
    }
}
