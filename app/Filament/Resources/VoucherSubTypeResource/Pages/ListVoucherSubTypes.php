<?php

namespace App\Filament\Resources\VoucherSubTypeResource\Pages;

use App\Filament\Resources\VoucherSubTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVoucherSubTypes extends ListRecords
{
    protected static string $resource = VoucherSubTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Voucher Sub Type')
                ->color('success'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
