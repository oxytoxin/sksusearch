<?php

namespace App\Filament\Resources\MfoFeeResource\Pages;

use App\Filament\Resources\MfoFeeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMfoFees extends ListRecords
{
    protected static string $resource = MfoFeeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New MFO Fee')
            ->color('success'),
        ];
    }
}
