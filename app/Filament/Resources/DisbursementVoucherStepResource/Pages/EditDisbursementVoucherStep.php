<?php

namespace App\Filament\Resources\DisbursementVoucherStepResource\Pages;

use App\Filament\Resources\DisbursementVoucherStepResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisbursementVoucherStep extends EditRecord
{
    protected static string $resource = DisbursementVoucherStepResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
