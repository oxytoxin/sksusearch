<?php

namespace App\Http\Livewire\WFP;

use Filament\Tables;
use Livewire\Component;
use App\Models\CostCenter;
use Filament\Forms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;

class FundAllocation extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return CostCenter::query();
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('name')
            ->wrap()
            ->searchable(),
            Tables\Columns\TextColumn::make('office.name')
            ->wrap()->label('Office')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('head')
            ->getStateUsing(function ($record) {
                return $record->office->head_employee?->full_name;
            }),
            Tables\Columns\TextColumn::make('mfo.name')->label('MFO')
            ->wrap()->searchable()->sortable(),
            Tables\Columns\TextColumn::make('fundClusterWFP.name')
            ->label('Fund Cluster')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('fundAllocations.amount')
            ->label('Amount')
            ->formatStateUsing(function ($record) {
                return '₱ '.number_format($record->fundAllocations->sum('amount'), 2);
            }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('allocate_fund')
            ->icon('ri-money-dollar-circle-line')
            ->label('Allocate Fund')
            ->button()
            ->form([
                TextInput::make('amount')
                ->label('Amount')
                ->required()
                ->mask(fn (TextInput\Mask $mask) => $mask
                    ->numeric()

                    ->thousandsSeparator(','),
                )
            ])
            ->action(function($data, $record) {
                $record->fundAllocations()->create([
                    'amount' => $data['amount']
                ]);

                Notification::make()->title('Operation Success')->body('Fund Successfully Added')->success()->send();

            })
            ->requiresConfirmation()
            ->visible(fn ($record) => !$record->fundAllocations()->exists()),
            Action::make('edit_fund')
            ->icon('ri-pencil-line')
            ->label('Edit fund')
            ->button()
            ->color('warning')
            ->mountUsing(fn ($record, Forms\ComponentContainer $form) => $form->fill([
                'amount' => $record->fundAllocations->sum('amount')
            ]))
            ->form([
                TextInput::make('amount')
                ->label('Amount')
                ->required()
                ->mask(fn (TextInput\Mask $mask) => $mask
                    ->numeric()
                    ->thousandsSeparator(','),
                )
            ])
            ->action(function($data, $record) {
                $record->fundAllocations()->update([
                    'amount' => $data['amount']
                ]);

                Notification::make()->title('Operation Success')->body('Fund Successfully Updated')->success()->send();

            })
            ->requiresConfirmation()
            ->visible(fn ($record) => $record->fundAllocations()->exists() && !$record->fundAllocations()->where('is_locked', true)->exists()),
            Action::make('lock_fund')
            ->icon('ri-lock-line')
            ->label('Lock fund')
            ->button()
            ->color('danger')
            ->requiresConfirmation()
            ->action(function($record) {
                $record->fundAllocations()->update([
                    'is_locked' => true
                ]);

                Notification::make()->title('Operation Success')->body('Fund Successfully Locked')->success()->send();

            })
            ->visible(fn ($record) => $record->fundAllocations()->exists() && !$record->fundAllocations()->where('is_locked', true)->exists())
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('fund')
            ->label('Fund Cluster')
            ->relationship('fundClusterWFP', 'name'),
            SelectFilter::make('mfo')
            ->label('MFO')
            ->relationship('mfo', 'name')
        ];
    }

    public function render()
    {
        return view('livewire.w-f-p.fund-allocation');
    }
}
