<?php

namespace App\Http\Livewire\Requisitioner\Motorpool;

use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction; 
use App\Models\RequestSchedule;

class RequestVehicleIndex extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return RequestSchedule::query()->whereHas('applicants', function ($query) {
            $query->whereIn('user_id', [auth()->user()->id]);
        });
    }

    protected function getTableColumns()
    {
        return [
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
            ActionGroup::make([
                Action::make('view')
                    ->icon('ri-eye-line')
                ->url(fn ($record) => route('requisitioner.motorpool.show', ['request' => $record]), false),
                ViewAction::make('print')
                    ->label('Print')
                    ->icon('ri-printer-fill')
                    ->openUrlInNewTab()
                ->url(fn ($record) => route('requisitioner.motorpool.show-request-form', ['request' => $record]), true),
            ])
        ];
    }

    public function render() 
    {
        return view('livewire.requisitioner.motorpool.request-vehicle-index');
    }
}
