<?php

namespace App\Filament\Resources\DisbursementVoucherStepResource\Pages;

use App\Filament\Resources\DisbursementVoucherStepResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisbursementVoucherSteps extends ListRecords
{
    protected static string $resource = DisbursementVoucherStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
