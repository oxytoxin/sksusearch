<?php

namespace App\Filament\Resources\SupplyResource\Pages;

use App\Filament\Resources\SupplyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupply extends EditRecord
{
    protected static string $resource = SupplyResource::class;

    public static ?string $title = 'Edit Item';

    protected function getHeaderActions(): array
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
