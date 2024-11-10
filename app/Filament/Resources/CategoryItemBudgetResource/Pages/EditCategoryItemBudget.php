<?php

namespace App\Filament\Resources\CategoryItemBudgetResource\Pages;

use App\Filament\Resources\CategoryItemBudgetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryItemBudget extends EditRecord
{
    protected static string $resource = CategoryItemBudgetResource::class;

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
