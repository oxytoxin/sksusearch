<?php

namespace App\Filament\Resources\VoucherCategoryResource\Pages;

use App\Filament\Resources\VoucherCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVoucherCategory extends CreateRecord
{
    protected static string $resource = VoucherCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
