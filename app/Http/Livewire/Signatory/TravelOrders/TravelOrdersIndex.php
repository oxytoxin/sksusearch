<?php

namespace App\Http\Livewire\Signatory\TravelOrders;

use App\Models\TravelOrder;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Livewire\Component;

class TravelOrdersIndex extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected function getTableQuery()
    {
        return TravelOrder::query()->whereRelation('signatories', 'user_id', auth()->id())->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('purpose')->limit(20)->searchable(),
            Tables\Columns\TextColumn::make('date_from')->label('From')->date()->searchable(),
            Tables\Columns\TextColumn::make('date_to')->label('To')->date()->searchable(),
            Tables\Columns\TextColumn::make('approved')->label('Status')
                ->formatStateUsing(fn ($record) => !$record->signatories->contains('pivot.is_approved', false) ? 'Approved' : 'Pending'),
            Tables\Columns\TextColumn::make('signed')->label('Signed')
                ->formatStateUsing(fn ($record) => $record->signatories()->wherePivot('user_id', auth()->id())->value('is_approved') ? 'Signed' : 'Pending'),

        ];
    }

    protected function getTableActions()
    {
        return [
            Action::make('view')
                ->url(fn (TravelOrder $record): string => route('signatory.travel-orders.view', $record))
                ->icon('heroicon-o-eye'),
            Action::make('print')
                ->url(fn (TravelOrder $record): string => route('signatory.travel-orders.show', $record))
                ->icon('heroicon-o-printer'),
        ];
    }

    public function render()
    {
        return view('livewire.signatory.travel-orders.travel-orders-index');
    }
}
