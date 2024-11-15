<?php

namespace App\Filament\Resources\PriceListDocumentResource\Pages;

use App\Filament\Resources\PriceListDocumentResource;
use Filament\Pages\Actions;
use App\Models\PriceListDocument;
use Filament\Resources\Pages\ListRecords;

class ListPriceListDocuments extends ListRecords
{
    protected static string $resource = PriceListDocumentResource::class;


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create New')
            ->color('success')
            ->visible(fn ($record) => PriceListDocument::count() < 1),
        ];
    }
}
