<?php

namespace App\Filament\Resources\FundClusterWFPResource\Pages;

use App\Filament\Resources\FundClusterWFPResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFundClusterWFPS extends ListRecords
{
    protected static string $resource = FundClusterWFPResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Fund Cluster')
            ->color('success'),
        ];
    }
}
