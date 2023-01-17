<?php

namespace App\Filament\Resources\ElectricityMeterResource\Pages;

use App\Filament\Resources\ElectricityMeterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListElectricityMeters extends ListRecords
{
    protected static string $resource = ElectricityMeterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Meter Number')
            ->color('success'),
        ];
    }
}
