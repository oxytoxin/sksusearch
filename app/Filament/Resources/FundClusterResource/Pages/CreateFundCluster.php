<?php

namespace App\Filament\Resources\FundClusterResource\Pages;

use App\Filament\Resources\FundClusterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFundCluster extends CreateRecord
{
    protected static string $resource = FundClusterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

