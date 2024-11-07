<?php

namespace App\Filament\Resources\CategoryItemsResource\Pages;

use App\Filament\Resources\CategoryItemsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryItems extends ListRecords
{
    protected static string $resource = CategoryItemsResource::class;

    public static ?string $title = 'Account Titles';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Account Title')
            ->color('success'),
        ];
    }
}
