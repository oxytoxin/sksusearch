<?php

namespace App\Filament\Resources\BudgetCategoryResource\Pages;

use App\Filament\Resources\BudgetCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBudgetCategories extends ListRecords
{
    protected static string $resource = BudgetCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Budget Category')
            ->color('success'),
        ];
    }
}
