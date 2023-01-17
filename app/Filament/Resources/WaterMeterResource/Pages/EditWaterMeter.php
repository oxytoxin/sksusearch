<?php

namespace App\Filament\Resources\WaterMeterResource\Pages;

use App\Filament\Resources\WaterMeterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWaterMeter extends EditRecord
{
    protected static string $resource = WaterMeterResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
