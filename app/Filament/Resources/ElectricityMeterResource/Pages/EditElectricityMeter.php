<?php

namespace App\Filament\Resources\ElectricityMeterResource\Pages;

use App\Filament\Resources\ElectricityMeterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditElectricityMeter extends EditRecord
{
    protected static string $resource = ElectricityMeterResource::class;

    protected function getHeaderActions(): array
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
