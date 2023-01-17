<?php

namespace App\Filament\Resources\ElectricityMeterResource\Pages;

use App\Filament\Resources\ElectricityMeterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateElectricityMeter extends CreateRecord
{
    protected static string $resource = ElectricityMeterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
