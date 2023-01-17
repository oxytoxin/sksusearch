<?php

namespace App\Filament\Resources\WaterMeterResource\Pages;

use App\Filament\Resources\WaterMeterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWaterMeters extends ListRecords
{
    protected static string $resource = WaterMeterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Meter Number')
            ->color('success'),
        ];
    }
}
