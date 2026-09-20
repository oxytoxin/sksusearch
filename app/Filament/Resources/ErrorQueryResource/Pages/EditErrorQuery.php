<?php

namespace App\Filament\Resources\ErrorQueryResource\Pages;

use App\Filament\Resources\ErrorQueryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditErrorQuery extends EditRecord
{
    protected static string $resource = ErrorQueryResource::class;

    protected function getHeaderActions(): array
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
