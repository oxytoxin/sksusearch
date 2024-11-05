<?php

namespace App\Filament\Resources\MfoFeeResource\Pages;

use App\Filament\Resources\MfoFeeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Layout;

class ListMfoFees extends ListRecords
{
    protected static string $resource = MfoFeeResource::class;

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('m_f_o_s_id')->label('MFO')->relationship('mfo', 'name'),
            SelectFilter::make('fund_cluster_w_f_p_s_id')->label('Fund Cluster')->relationship('fundClusterWFP', 'name'),
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New MFO Fee')
            ->color('success'),
        ];
    }
}
