<?php

namespace App\Http\Livewire\Motorpool\Requests;

use App\Models\FuelRequisition;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\ViewAction;

class FuelRequestIndex extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return FuelRequisition::query();
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('user.name')
                ->searchable()
                ->wrap(),
            Tables\Columns\TextColumn::make('slip_number')
                ->label('Slip Number')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('article')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('quantity')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('unit')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('purpose')
                ->wrap()
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->date()
                ->label('Date Created'),

        ];
    }

    protected function getTableActions(): array
    {
        return [
            ViewAction::make('print')
            ->label('Fuel Requisition Form')
            ->icon('ri-printer-fill')
            ->button()
            ->color('success')
            //->openUrlInNewTab()
            //->url(fn ($record) => route('motorpool.request.show', ['request' => $record]), true)
        ];
    }

    public function render()
    {
        return view('livewire.motorpool.requests.fuel-request-index');
    }
}
