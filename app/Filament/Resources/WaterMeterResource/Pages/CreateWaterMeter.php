<?php

namespace App\Filament\Resources\WaterMeterResource\Pages;

use App\Filament\Resources\WaterMeterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWaterMeter extends CreateRecord
{
    protected static string $resource = WaterMeterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
