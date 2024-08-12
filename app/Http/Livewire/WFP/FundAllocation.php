<?php

namespace App\Http\Livewire\WFP;

use App\Models\CostCenter;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;

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
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('allocate_fund')
            ->icon('ri-money-dollar-circle-line')
            ->label('Allocate Fund')
            ->button()
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
