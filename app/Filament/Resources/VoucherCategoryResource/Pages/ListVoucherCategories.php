<?php

namespace App\Filament\Resources\VoucherCategoryResource\Pages;

use App\Filament\Resources\VoucherCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVoucherCategories extends ListRecords
{
    protected static string $resource = VoucherCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->color('success'),
        ];
    }
}
