<?php

namespace App\Filament\Resources\SupplyResource\Pages;

use App\Filament\Resources\SupplyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplies extends ListRecords
{
    protected static string $resource = SupplyResource::class;

    public static ?string $title = 'Account Items';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Add Item')
            ->color('success'),
        ];
    }
}
