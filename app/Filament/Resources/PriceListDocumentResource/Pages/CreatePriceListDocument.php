<?php

namespace App\Filament\Resources\PriceListDocumentResource\Pages;

use App\Filament\Resources\PriceListDocumentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePriceListDocument extends CreateRecord
{
    protected static string $resource = PriceListDocumentResource::class;

    protected static ?string $title = "Create Document";

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected static function canCreateAnother(): bool
    {
        return false;
    }
}
