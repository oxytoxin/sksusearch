<?php

namespace App\Filament\Resources\FundClusterResource\Pages;

use App\Filament\Resources\FundClusterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFundClusters extends ListRecords
{
    protected static string $resource = FundClusterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Fund Cluster')
            ->color('success'),
        ];
    }
}
