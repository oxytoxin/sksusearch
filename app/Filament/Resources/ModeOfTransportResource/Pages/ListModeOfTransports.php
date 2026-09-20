<?php

namespace App\Filament\Resources\ModeOfTransportResource\Pages;

use App\Filament\Resources\ModeOfTransportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModeOfTransports extends ListRecords
{
    protected static string $resource = ModeOfTransportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Mode Of Transport')
            ->color('success'),
        ];
    }
}
