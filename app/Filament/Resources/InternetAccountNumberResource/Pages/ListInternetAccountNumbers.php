<?php

namespace App\Filament\Resources\InternetAccountNumberResource\Pages;

use App\Filament\Resources\InternetAccountNumberResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInternetAccountNumbers extends ListRecords
{
    protected static string $resource = InternetAccountNumberResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Account Number')
            ->color('success'),
        ];
    }
}
