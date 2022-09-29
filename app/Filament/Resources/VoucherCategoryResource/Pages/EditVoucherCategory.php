<?php

namespace App\Filament\Resources\VoucherCategoryResource\Pages;

use App\Filament\Resources\VoucherCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVoucherCategory extends EditRecord
{
    protected static string $resource = VoucherCategoryResource::class;

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
