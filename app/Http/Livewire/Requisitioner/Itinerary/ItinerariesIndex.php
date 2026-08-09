<?php

namespace App\Http\Livewire\Requisitioner\Itinerary;

use App\Models\Itinerary;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ItinerariesIndex extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected function getTableQuery()
    {
        return Itinerary::with('travel_order')
            ->where('user_id', auth()->id())
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('travel_order.purpose')
                ->label('Purpose')
                ->wrap(),
            Tables\Columns\TextColumn::make('travel_order.date_from')
                ->label('From')
                ->date(),
            Tables\Columns\TextColumn::make('travel_order.date_to')
                ->label('To')
                ->date(),
            Tables\Columns\TextColumn::make('is_actual')
                ->label('Type')
                ->formatStateUsing(fn (bool $state) => $state ? 'Actual' : 'Proposed'),
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->getStateUsing(fn (Itinerary $record) => blank($record->submitted_at)
                    ? 'Draft'
                    : (blank($record->approved_at) ? 'Submitted' : 'Approved')),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Created')
                ->dateTime(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->options([
                    'draft' => 'Draft',
                    'submitted' => 'Submitted',
                    'approved' => 'Approved',
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return match ($data['value'] ?? null) {
                        'draft' => $query->whereNull('submitted_at'),
                        'submitted' => $query->whereNotNull('submitted_at')->whereNull('approved_at'),
                        'approved' => $query->whereNotNull('approved_at'),
                        default => $query,
                    };
                }),
            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from')->label('Created From'),
                    DatePicker::make('created_until')->label('Created Until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['created_from'] ?? null, fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date))
                        ->when($data['created_until'] ?? null, fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date));
                }),
        ];
    }

    protected function getTableActions()
    {
        return [
            Action::make('edit')
                ->label('Edit')
                ->button()
                ->visible(fn (Itinerary $record): bool => blank($record->submitted_at))
                ->url(fn (Itinerary $record): string => route('requisitioner.itinerary.edit', ['itinerary' => $record]))
                ->icon('heroicon-o-pencil'),
        ];
    }

    public function render()
    {
        return view('livewire.requisitioner.itinerary.itineraries-index');
    }
}
