<?php

namespace App\Filament\Resources\VoucherSubTypeResource\Pages;

use App\Filament\Resources\VoucherSubTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVoucherSubType extends EditRecord
{
    protected static string $resource = VoucherSubTypeResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
