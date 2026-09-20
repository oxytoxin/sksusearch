<?php

namespace App\Filament\Resources\SupplementalQuarterResource\Pages;

use App\Filament\Resources\SupplementalQuarterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplementalQuarters extends ListRecords
{
    protected static string $resource = SupplementalQuarterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Add Quarter')
            ->color('success'),
        ];
    }
}
