<?php

namespace App\Filament\Resources\PriceListDocumentResource\Pages;

use App\Filament\Resources\PriceListDocumentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPriceListDocument extends EditRecord
{
    protected static string $resource = PriceListDocumentResource::class;

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
