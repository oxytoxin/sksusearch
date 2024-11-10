<?php

namespace App\Filament\Resources\CategoryItemBudgetResource\Pages;

use App\Filament\Resources\CategoryItemBudgetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoryItemBudget extends CreateRecord
{
    protected static string $resource = CategoryItemBudgetResource::class;

    public static ?string $title = 'Create Account Title - Budget';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
