<?php

namespace App\Filament\Resources\ModeOfTransportResource\Pages;

use App\Filament\Resources\ModeOfTransportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModeOfTransport extends EditRecord
{
    protected static string $resource = ModeOfTransportResource::class;

    protected function getHeaderActions(): array
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
