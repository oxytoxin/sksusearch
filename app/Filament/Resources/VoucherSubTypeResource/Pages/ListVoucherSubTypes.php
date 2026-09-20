<?php

namespace App\Filament\Resources\VoucherSubTypeResource\Pages;

use App\Filament\Resources\VoucherSubTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListVoucherSubTypes extends ListRecords
{
    protected static string $resource = VoucherSubTypeResource::class;

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->orderBy('voucher_type_id')
                ->orderBy('order_column'))
            ->paginatedWhileReordering();
    }

    protected function getHeaderActions(): array
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
