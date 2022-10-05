<?php

namespace App\Filament\Resources\ProvinceResource\Pages;

use App\Filament\Resources\ProvinceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProvince extends EditRecord
{
    protected static string $resource = ProvinceResource::class;

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
