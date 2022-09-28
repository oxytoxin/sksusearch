<?php

namespace App\Filament\Resources\ModeOfTransportResource\Pages;

use App\Filament\Resources\ModeOfTransportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModeOfTransport extends EditRecord
{
    protected static string $resource = ModeOfTransportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
