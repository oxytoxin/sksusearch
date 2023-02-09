<?php

namespace App\Filament\Resources\TelephoneAccountNumberResource\Pages;

use App\Filament\Resources\TelephoneAccountNumberResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTelephoneAccountNumber extends CreateRecord
{
    protected static string $resource = TelephoneAccountNumberResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
