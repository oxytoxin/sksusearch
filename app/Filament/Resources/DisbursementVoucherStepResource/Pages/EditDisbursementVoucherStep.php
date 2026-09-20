<?php

namespace App\Filament\Resources\DisbursementVoucherStepResource\Pages;

use App\Filament\Resources\DisbursementVoucherStepResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisbursementVoucherStep extends EditRecord
{
    protected static string $resource = DisbursementVoucherStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
