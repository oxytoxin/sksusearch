<?php

namespace App\Filament\Resources\ElectricityMeterResource\Pages;

use App\Filament\Resources\ElectricityMeterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditElectricityMeter extends EditRecord
{
    protected static string $resource = ElectricityMeterResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
