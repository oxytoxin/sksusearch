<?php

namespace App\Filament\Resources\DisbursementVoucherStepResource\Pages;

use App\Filament\Resources\DisbursementVoucherStepResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisbursementVoucherSteps extends ListRecords
{
    protected static string $resource = DisbursementVoucherStepResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
