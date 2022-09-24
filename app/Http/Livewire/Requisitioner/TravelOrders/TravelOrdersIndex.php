<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\TravelOrder;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class TravelOrdersIndex extends Component implements Tables\Contracts\HasTable 
{
    use Tables\Concerns\InteractsWithTable; 

    protected function getTableQuery()
    {
        return TravelOrder::query();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('purpose')->limit(20)->searchable(),
            Tables\Columns\TextColumn::make('date_from')->label('From')->date()->searchable(),
            Tables\Columns\TextColumn::make('date_to')->label('To')->date()->searchable(),
            
        ];
    }

    protected function getTableActions()
    {
        return[
            Action::make('view')
            ->url(fn (TravelOrder $record): string => route('requisitioner.travel-orders.show', $record))
            ->icon('heroicon-o-eye'),
        ];
    }
    
    public function render()
    {
        return view('livewire.requisitioner.travel-orders.travel-orders-index');
    }
}
