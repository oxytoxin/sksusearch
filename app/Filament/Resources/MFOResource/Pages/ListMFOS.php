<?php

namespace App\Filament\Resources\MFOResource\Pages;

use App\Filament\Resources\MFOResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMFOS extends ListRecords
{
    protected static string $resource = MFOResource::class;

    protected static ?string $title = 'MFO';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New MFO')
            ->color('success'),
        ];
    }
}
