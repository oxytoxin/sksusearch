<?php

namespace App\Filament\Resources\MFOResource\Pages;

use App\Filament\Resources\MFOResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMFO extends CreateRecord
{
    protected static string $resource = MFOResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
