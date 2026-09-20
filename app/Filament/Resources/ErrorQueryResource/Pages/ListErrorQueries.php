<?php

namespace App\Filament\Resources\ErrorQueryResource\Pages;

use App\Filament\Resources\ErrorQueryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListErrorQueries extends ListRecords
{
    protected static string $resource = ErrorQueryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Add Error Query')
            ->color('success'),
        ];
    }
}
