<?php

namespace App\Filament\Resources\MFOResource\Pages;

use App\Filament\Resources\MFOResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMFO extends EditRecord
{
    protected static string $resource = MFOResource::class;

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
