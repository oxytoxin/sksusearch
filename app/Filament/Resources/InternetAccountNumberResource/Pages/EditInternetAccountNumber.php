<?php

namespace App\Filament\Resources\InternetAccountNumberResource\Pages;

use App\Filament\Resources\InternetAccountNumberResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternetAccountNumber extends EditRecord
{
    protected static string $resource = InternetAccountNumberResource::class;

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
