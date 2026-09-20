<?php

namespace App\Filament\Resources\CategoryGroupResource\Pages;

use App\Filament\Resources\CategoryGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryGroup extends EditRecord
{
    protected static string $resource = CategoryGroupResource::class;

    public static ?string $title = 'Edit Account Group';

    protected function getHeaderActions(): array
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
