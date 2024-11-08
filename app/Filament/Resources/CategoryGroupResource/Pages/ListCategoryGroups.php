<?php

namespace App\Filament\Resources\CategoryGroupResource\Pages;

use Filament\Pages\Actions;
use App\Models\CategoryGroup;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryGroupResource;

class ListCategoryGroups extends ListRecords
{
    protected static string $resource = CategoryGroupResource::class;

    public static ?string $title = 'Account Groups';

    protected function getTableQuery(): Builder
    {
        return CategoryGroup::query()->orderBy('sort_id');
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Account Group')
            ->color('success'),
        ];
    }
}
