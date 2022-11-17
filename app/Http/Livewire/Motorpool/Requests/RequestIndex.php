<?php

namespace App\Http\Livewire\Motorpool\Requests;

use App\Models\RequestSchedule;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\Action;
use Livewire\Component;

class RequestIndex extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return RequestSchedule::query();
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('purpose')
                ->searchable(),
            Tables\Columns\TextColumn::make('date_of_travel')
                ->label('Date of travel')
                ->date()
                ->sortable()
                ->searchable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('edit')
                ->icon('heroicon-s-pencil')
            // ->url(fn (Vehicle $record): string => route('motorpool.vehicle.edit', $record))
        ];
    }

    public function render()
    {
        return view('livewire.motorpool.requests.request-index');
    }
}
