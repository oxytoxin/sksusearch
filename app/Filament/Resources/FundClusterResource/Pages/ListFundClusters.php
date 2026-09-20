<?php

namespace App\Filament\Resources\FundClusterResource\Pages;

use App\Filament\Resources\FundClusterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFundClusters extends ListRecords
{
    protected static string $resource = FundClusterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Fund Cluster')
            ->color('success'),
        ];
    }
}
