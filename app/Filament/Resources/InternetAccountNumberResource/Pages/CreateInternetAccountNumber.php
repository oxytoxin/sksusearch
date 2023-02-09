<?php

namespace App\Filament\Resources\InternetAccountNumberResource\Pages;

use App\Filament\Resources\InternetAccountNumberResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInternetAccountNumber extends CreateRecord
{
    protected static string $resource = InternetAccountNumberResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
