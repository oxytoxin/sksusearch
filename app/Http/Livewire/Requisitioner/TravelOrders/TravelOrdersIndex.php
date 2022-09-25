<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\TravelOrder;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
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
            Tables\Columns\TextColumn::make('approved')->label('Status')
            ->formatStateUsing(fn($record) => !$record->signatories->contains('pivot.is_approved', false) ? 'Approved' : 'Pending'),
            
        ];
    }

    protected function getTableActions()
    {
        return[
            Action::make('view')
            ->url(fn (TravelOrder $record): string => route('requisitioner.travel-orders.show', $record))
            ->icon('heroicon-o-eye'),
            Action::make('print')
            ->visible(function($record){
                return !$record->signatories->contains('pivot.is_approved', false);
            })
            ->url(fn (TravelOrder $record): string => route('requisitioner.travel-orders.show', $record))
            ->icon('heroicon-o-printer'),
        ];
    }

    protected function getTableFilters(): array
{
    return [
        Filter::make('pivot.is_approved')->toggle()
    ];
}
    
    public function render()
    {
        return view('livewire.requisitioner.travel-orders.travel-orders-index');
    }
}
