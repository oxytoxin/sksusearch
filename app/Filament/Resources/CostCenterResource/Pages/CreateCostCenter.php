<?php

namespace App\Filament\Resources\CostCenterResource\Pages;

use App\Filament\Resources\CostCenterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCostCenter extends CreateRecord
{
    protected static string $resource = CostCenterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
