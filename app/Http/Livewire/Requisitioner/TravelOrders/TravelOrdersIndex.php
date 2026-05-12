<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\TravelOrder;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
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
            Tables\Columns\TextColumn::make('purpose')->wrap()->searchable(),
            Tables\Columns\TextColumn::make('date_from')->label('From')->date()->searchable(),
            Tables\Columns\TextColumn::make('date_to')->label('To')->date()->searchable(),
            Tables\Columns\TextColumn::make('approved')->label('Status')
                ->formatStateUsing(fn ($record) => $record->signatories->contains('pivot.is_approved', 2) ? 'Cancelled'
                    : ($record->signatories->contains('pivot.is_approved', 0) ? 'Pending'
                        : 'Approved')),

        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'cancelled' => 'Cancelled',
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return match ($data['value'] ?? null) {
                        'cancelled' => $query->whereHas('signatories', fn (Builder $q) => $q->where('travel_order_signatories.is_approved', 2)),
                        'pending' => $query
                            ->whereDoesntHave('signatories', fn (Builder $q) => $q->where('travel_order_signatories.is_approved', 2))
                            ->whereHas('signatories', fn (Builder $q) => $q->where('travel_order_signatories.is_approved', 0)),
                        'approved' => $query
                            ->whereDoesntHave('signatories', fn (Builder $q) => $q->where('travel_order_signatories.is_approved', 2))
                            ->whereDoesntHave('signatories', fn (Builder $q) => $q->where('travel_order_signatories.is_approved', 0)),
                        default => $query,
                    };
                }),
        ];
    }

    protected function getTableActions()
    {
        $proposedItinerary = fn (TravelOrder $record) => $record->itineraries()
            ->where('is_actual', false)
            ->where('user_id', auth()->id())
            ->first();

        $actualItinerary = fn (TravelOrder $record) => $record->itineraries()
            ->where('is_actual', true)
            ->where('user_id', auth()->id())
            ->first();

        return [
            Action::make('view')
                ->label('View')
                ->button()
                ->url(fn (TravelOrder $record): string => route('requisitioner.travel-orders.view', $record))
                ->icon('heroicon-o-eye'),
            ActionGroup::make([
                Action::make('print_travel_order')
                    ->label('Print Travel Order')
                    ->icon('heroicon-o-printer')
                    ->openUrlInNewTab()
                    ->url(fn (TravelOrder $record): string => route('requisitioner.travel-orders.show', $record)),
                Action::make('print_proposed_iot')
                    ->label('Print Proposed IOT')
                    ->icon('heroicon-o-printer')
                    ->openUrlInNewTab()
                    ->visible(fn (TravelOrder $record): bool => $proposedItinerary($record) !== null)
                    ->url(fn (TravelOrder $record): string => route('requisitioner.itinerary.print', ['itinerary' => $proposedItinerary($record)])),
                Action::make('print_actual_iot')
                    ->label('Print Actual IOT')
                    ->icon('heroicon-o-printer')
                    ->openUrlInNewTab()
                    ->visible(fn (TravelOrder $record): bool => $actualItinerary($record) !== null)
                    ->url(fn (TravelOrder $record): string => route('requisitioner.itinerary.print', ['itinerary' => $actualItinerary($record)])),
            ])
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->tooltip('Print options'),
        ];
    }

    public function render()
    {
        return view('livewire.requisitioner.travel-orders.travel-orders-index');
    }
}
