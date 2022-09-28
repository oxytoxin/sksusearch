<?php

namespace App\Filament\Resources\DailyTravelExpensesResource\Pages;

use App\Filament\Resources\DailyTravelExpensesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyTravelExpenses extends CreateRecord
{
    protected static string $resource = DailyTravelExpensesResource::class;
}
