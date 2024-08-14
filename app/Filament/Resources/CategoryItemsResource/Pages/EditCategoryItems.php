<?php

namespace App\Filament\Resources\CategoryItemsResource\Pages;

use App\Filament\Resources\CategoryItemsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryItems extends EditRecord
{
    protected static string $resource = CategoryItemsResource::class;

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
