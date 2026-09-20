<?php

namespace App\Http\Livewire\Oic;

use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\DisbursementVoucherStep;
use App\Models\OicUser;
use App\Models\TravelOrder;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;

class OicSignatoryTravelOrders extends Component implements HasForms, HasTable
{
    use InteractsWithForms, OfficeDashboardActions;
    use InteractsWithTable {
        table as baseTable;
    }

    protected function getTableQuery()
    {
        return TravelOrder::query()->whereNotNull('submitted_at');
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('purpose')->limit(20)->searchable(),
            TextColumn::make('date_from')->label('From')->date()->searchable(),
            TextColumn::make('date_to')->label('To')->date()->searchable(),
            TextColumn::make('approved')->label('Status')
                ->formatStateUsing(fn ($record) => $record->signatories->contains('pivot.is_approved', 2) ? 'Cancelled'
                    : ($record->signatories->contains('pivot.is_approved', 0) ? 'Pending'
                        : 'Approved')),
            TextColumn::make('signed')->label('Signed')
                ->formatStateUsing(fn ($record, $livewire) => $record->signatories()->wherePivot('user_id', $livewire->tableFilters['as']['value'])->value('is_approved') ? 'Signed' : 'Pending'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('as')
                ->searchable()
                ->placeholder('Select User')
                ->options(EmployeeInformation::whereIn('user_id', OicUser::valid()->distinct('user_id')->pluck('user_id'))->pluck('full_name', 'user_id'))
                ->query(function ($query, $state) {
                    $query->whereRelation('signatories', 'user_id', $state);
                }),
        ];
    }

    public function getTableActions()
    {
        return [
            Action::make('view')
                ->url(fn (TravelOrder $record, $livewire): string => route('signatory.travel-orders.view', [
                    'travel_order' => $record,
                    'from_oic' => true,
                    'oic_signatory' => $livewire->tableFilters['as']['value']
                ]))
                ->icon('heroicon-o-eye'),
            Action::make('print')
                ->url(fn (TravelOrder $record): string => route('requisitioner.travel-orders.show', $record))
                ->icon('heroicon-o-printer'),
        ];
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $this->baseTable($table)
            ->filtersFormColumns(1)
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent);
    }

    public function render()
    {
        return view('livewire.oic.oic-signatory-travel-orders');
    }
}
