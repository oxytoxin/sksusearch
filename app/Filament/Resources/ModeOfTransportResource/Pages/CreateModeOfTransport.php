<?php

namespace App\Filament\Resources\ModeOfTransportResource\Pages;

use App\Filament\Resources\ModeOfTransportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateModeOfTransport extends CreateRecord
{
    protected static string $resource = ModeOfTransportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
}
