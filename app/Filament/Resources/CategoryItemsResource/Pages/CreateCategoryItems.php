<?php

namespace App\Filament\Resources\CategoryItemsResource\Pages;

use App\Filament\Resources\CategoryItemsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoryItems extends CreateRecord
{
    protected static string $resource = CategoryItemsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
