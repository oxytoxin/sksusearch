<?php

namespace App\Filament\Resources\LiquidationReportStepResource\Pages;

use App\Filament\Resources\LiquidationReportStepResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLiquidationReportSteps extends ListRecords
{
    protected static string $resource = LiquidationReportStepResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
