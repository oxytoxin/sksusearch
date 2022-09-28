<?php

namespace App\Filament\Resources\DailyTravelExpensesResource\Pages;

use App\Filament\Resources\DailyTravelExpensesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyTravelExpenses extends ListRecords
{
    protected static string $resource = DailyTravelExpensesResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
