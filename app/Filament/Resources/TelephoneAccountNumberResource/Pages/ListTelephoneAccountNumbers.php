<?php

namespace App\Filament\Resources\TelephoneAccountNumberResource\Pages;

use App\Filament\Resources\TelephoneAccountNumberResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTelephoneAccountNumbers extends ListRecords
{
    protected static string $resource = TelephoneAccountNumberResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Account Number')
            ->color('success'),
        ];
    }
}
