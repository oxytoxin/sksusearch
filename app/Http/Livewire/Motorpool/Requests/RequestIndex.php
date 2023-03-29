<?php

namespace App\Http\Livewire\Motorpool\Requests;

use App\Models\RequestSchedule;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Livewire\Component;

class RequestIndex extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return RequestSchedule::query()->where('status', 'Approved');
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('purpose')
                ->searchable()
                ->wrap(),
            Tables\Columns\TextColumn::make('vehicle.model')
                ->label('Vehicle')
                ->sortable()
                ->default('NOT SET')
                ->searchable(),
            Tables\Columns\TextColumn::make('vehicle.plate_number')
                ->label('Plate Number')
                ->sortable()
                ->default('NOT SET')
                ->searchable(),
            Tables\Columns\TextColumn::make('driver.full_name')
                ->label('Driver')
                ->sortable()
                ->default('NOT SET')
                ->searchable(),
            Tables\Columns\TextColumn::make('date_of_travel_from')
                ->label('From')
                ->date()
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('date_of_travel_to')
                ->label('To')
                ->date()
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->label('Status'),

        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
            ->icon('ri-eye-line')
            ->url(fn ($record) => route('requisitioner.motorpool.show', ['request' => $record]), false),
            ViewAction::make('print')
            ->label('Print')
            ->icon('ri-printer-fill')
            ->openUrlInNewTab()
            ->url(fn ($record) => route('motorpool.request.show', ['request' => $record]), true)
            ->visible(fn ($record) => $record->driver_id != null),
        ];
    }

    public function render()
    {
        return view('livewire.motorpool.requests.request-index');
    }
}
