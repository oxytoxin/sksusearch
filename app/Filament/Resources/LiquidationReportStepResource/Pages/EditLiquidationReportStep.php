<?php

namespace App\Filament\Resources\LiquidationReportStepResource\Pages;

use App\Filament\Resources\LiquidationReportStepResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLiquidationReportStep extends EditRecord
{
    protected static string $resource = LiquidationReportStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
