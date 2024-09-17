<?php

namespace App\Filament\Resources\MfoFeeResource\Pages;

use App\Filament\Resources\MfoFeeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMfoFee extends CreateRecord
{
    protected static string $resource = MfoFeeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
