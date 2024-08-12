<?php

namespace App\Filament\Resources\FundClusterWFPResource\Pages;

use App\Filament\Resources\FundClusterWFPResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFundClusterWFP extends CreateRecord
{
    protected static string $resource = FundClusterWFPResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
