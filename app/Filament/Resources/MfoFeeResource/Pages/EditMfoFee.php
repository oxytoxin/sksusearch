<?php

namespace App\Filament\Resources\MfoFeeResource\Pages;

use App\Filament\Resources\MfoFeeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMfoFee extends EditRecord
{
    protected static string $resource = MfoFeeResource::class;

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
