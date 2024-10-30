<?php

namespace App\Filament\Resources\ErrorQueryResource\Pages;

use App\Filament\Resources\ErrorQueryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditErrorQuery extends EditRecord
{
    protected static string $resource = ErrorQueryResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
