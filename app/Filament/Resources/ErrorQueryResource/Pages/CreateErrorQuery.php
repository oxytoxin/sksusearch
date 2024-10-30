<?php

namespace App\Filament\Resources\ErrorQueryResource\Pages;

use App\Filament\Resources\ErrorQueryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateErrorQuery extends CreateRecord
{
    protected static string $resource = ErrorQueryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
