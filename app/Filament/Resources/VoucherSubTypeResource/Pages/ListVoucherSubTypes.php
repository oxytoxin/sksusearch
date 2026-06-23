<?php

namespace App\Filament\Resources\VoucherSubTypeResource\Pages;

use App\Filament\Resources\VoucherSubTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVoucherSubTypes extends ListRecords
{
    protected static string $resource = VoucherSubTypeResource::class;

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->orderBy('voucher_type_id')->orderBy('order_column');
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Voucher Sub Type')
                ->color('success'),
        ];
    }

    protected function isTablePaginationEnabledWhileReordering(): bool
    {
        return true;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
