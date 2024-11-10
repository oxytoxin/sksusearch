<?php

namespace App\Filament\Resources\CategoryItemBudgetResource\Pages;

use App\Filament\Resources\CategoryItemBudgetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryItemBudgets extends ListRecords
{
    protected static string $resource = CategoryItemBudgetResource::class;

    public static ?string $title = 'Account Title - Budget';


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Account Title - Budget')
            ->color('success'),
        ];
    }
}
