<?php

namespace App\Filament\Resources\CategoryGroupResource\Pages;

use App\Filament\Resources\CategoryGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoryGroup extends CreateRecord
{
    protected static string $resource = CategoryGroupResource::class;

    public static ?string $title = 'Create Account Group';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
