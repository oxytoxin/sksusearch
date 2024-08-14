<?php

namespace App\Filament\Resources\SupplyResource\Pages;

use App\Filament\Resources\SupplyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplies extends ListRecords
{
    protected static string $resource = SupplyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Add Supply')
            ->color('success'),
        ];
    }
}
