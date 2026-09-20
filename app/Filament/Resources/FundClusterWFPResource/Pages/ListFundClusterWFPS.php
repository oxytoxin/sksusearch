<?php

namespace App\Filament\Resources\FundClusterWFPResource\Pages;

use App\Filament\Resources\FundClusterWFPResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFundClusterWFPS extends ListRecords
{
    protected static string $resource = FundClusterWFPResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Fund Cluster')
            ->color('success'),
        ];
    }
}
