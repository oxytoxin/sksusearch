<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\TravelOrder;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Livewire\Component;

class TravelOrdersIndex extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected function getTableQuery()
    {
        return TravelOrder::whereRelation('applicants', 'user_id', auth()->id())->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('purpose')->limit(20)->searchable(),
            Tables\Columns\TextColumn::make('date_from')->label('From')->date()->searchable(),
            Tables\Columns\TextColumn::make('date_to')->label('To')->date()->searchable(),
            Tables\Columns\TextColumn::make('approved')->label('Status')
                ->formatStateUsing(fn ($record) => $record->signatories->contains('pivot.is_approved', 2) ? 'Cancelled'
                    : ($record->signatories->contains('pivot.is_approved', 0) ? 'Pending'
                        : 'Approved')),

        ];
    }

    protected function getTableActions()
    {
        return [
            // Action::make('cancel')
            //     ->icon('heroicon-o-x-circle')
            //     ->button()
            //     ->color('danger')
            //     ->action(fn (TravelOrder $record) => $record->applicants()->detach(auth()->id())),
            Action::make('view')
                ->url(fn (TravelOrder $record): string => route('requisitioner.travel-orders.view', $record))
                ->icon('heroicon-o-eye'),
            Action::make('print')
                ->url(fn (TravelOrder $record): string => route('requisitioner.travel-orders.show', $record))
                ->icon('heroicon-o-printer'),
        ];
    }

    public function render()
    {
        return view('livewire.requisitioner.travel-orders.travel-orders-index');
    }
}
