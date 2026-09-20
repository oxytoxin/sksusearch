<?php

namespace App\Filament\Resources\VoucherTypeResource\Pages;

use App\Filament\Resources\VoucherTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVoucherTypes extends ListRecords
{
    protected static string $resource = VoucherTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Voucher Type')
                ->color('success'),
        ];
    }
}
