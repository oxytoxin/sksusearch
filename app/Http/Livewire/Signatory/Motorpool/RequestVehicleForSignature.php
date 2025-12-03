<?php

namespace App\Http\Livewire\Signatory\Motorpool;

use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use App\Models\RequestSchedule;
use Filament\Tables\Columns\ViewColumn;

class RequestVehicleForSignature extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return RequestSchedule::query()->where('status', 'Pending');
    }

    protected function getTableColumns()
    {
        return [
            ViewColumn::make('passengers')->view('tables.columns.passengers'),
            Tables\Columns\TextColumn::make('purpose')
                ->wrap()
                ->searchable(),
            Tables\Columns\TextColumn::make('date_of_travel_from')
                ->label('Date of travel from')
                ->date()
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('date_of_travel_to')
                ->label('Date of travel to')
                ->date()
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->searchable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
                Action::make('view')
                    ->icon('ri-eye-line')
                    ->url(fn ($record) => route('requisitioner.motorpool.show', ['request' => $record]), true)
                    ->openUrlInNewTab(false),
        ];
    }

    public function render()
    {
        return view('livewire.signatory.motorpool.request-vehicle-for-signature');
    }
}
