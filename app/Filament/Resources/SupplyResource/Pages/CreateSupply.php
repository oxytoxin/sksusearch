<?php

namespace App\Filament\Resources\SupplyResource\Pages;

use App\Filament\Resources\SupplyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSupply extends CreateRecord
{
    protected static string $resource = SupplyResource::class;

    public static ?string $title = 'Create Item';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
