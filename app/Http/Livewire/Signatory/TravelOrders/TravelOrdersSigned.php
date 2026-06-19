<?php

namespace App\Http\Livewire\Signatory\TravelOrders;

use App\Models\TravelOrder;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Livewire\Component;

class TravelOrdersSigned extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected function getTableQuery()
    {
        return TravelOrder::query()
            ->with('applicants')
            ->whereRelation('signatories', 'user_id', auth()->id())
            ->whereDoesntHave('signatories', function ($query) {
                $query->where('user_id', auth()->id())
                    ->where('is_approved', 0);
            })
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('tracking_code')
                ->label('Tracking Code')
                ->searchable(),
            Tables\Columns\TextColumn::make('applicants_list')
                ->label('Applicant(s)')
                ->getStateUsing(fn ($record) => $record->applicants->pluck('name')->join(', '))
                ->limit(30)
                ->searchable(query: function ($query, string $search) {
                    return $query->whereHas('applicants', function ($q) use ($search) {
                        $q->whereHas('employee_information', function ($eq) use ($search) {
                            $eq->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    });
                }),
            Tables\Columns\TextColumn::make('purpose')->limit(20)->searchable(),
            Tables\Columns\TextColumn::make('date_from')->label('From')->date()->searchable(),
            Tables\Columns\TextColumn::make('date_to')->label('To')->date()->searchable(),
            Tables\Columns\TextColumn::make('approved')->label('Status')
                ->formatStateUsing(fn($record) => $record->signatories->contains('pivot.is_approved', 2) ? 'Cancelled'
                    : ($record->signatories->contains('pivot.is_approved', 0) ? 'Pending'
                        : 'Approved')),
            Tables\Columns\TextColumn::make('signed')->label('Signed')
                ->formatStateUsing(fn($record) => $record->signatories()->wherePivot('user_id', auth()->id())->value('is_approved') == 2 ? 'Cancelled' : 'Signed'),
        ];
    }

    protected function getTableActions()
    {
        return [
            Action::make('view')
                ->url(fn(TravelOrder $record): string => route('signatory.travel-orders.view', $record))
                ->icon('heroicon-o-eye'),
            Action::make('print')
                ->url(fn(TravelOrder $record): string => route('signatory.travel-orders.show', $record))
                ->icon('heroicon-o-printer'),
        ];
    }

    public function render()
    {
        return view('livewire.signatory.travel-orders.travel-orders-signed');
    }
}
