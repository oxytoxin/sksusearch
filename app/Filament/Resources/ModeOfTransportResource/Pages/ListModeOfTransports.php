<?php

namespace App\Filament\Resources\ModeOfTransportResource\Pages;

use App\Filament\Resources\ModeOfTransportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModeOfTransports extends ListRecords
{
    protected static string $resource = ModeOfTransportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Mode Of Transport')
            ->color('success'),
        ];
    }
}
