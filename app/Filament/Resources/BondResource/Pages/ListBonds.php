<?php

namespace App\Filament\Resources\BondResource\Pages;

use App\Filament\Resources\BondResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBonds extends ListRecords
{
    protected static string $resource = BondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Bond')
                ->color('success'),
        ];
    }
}
