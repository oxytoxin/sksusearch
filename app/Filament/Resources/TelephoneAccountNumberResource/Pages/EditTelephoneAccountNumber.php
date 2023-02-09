<?php

namespace App\Filament\Resources\TelephoneAccountNumberResource\Pages;

use App\Filament\Resources\TelephoneAccountNumberResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTelephoneAccountNumber extends EditRecord
{
    protected static string $resource = TelephoneAccountNumberResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
