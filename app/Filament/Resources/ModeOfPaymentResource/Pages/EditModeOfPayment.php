<?php

namespace App\Filament\Resources\ModeOfPaymentResource\Pages;

use App\Filament\Resources\ModeOfPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModeOfPayment extends EditRecord
{
    protected static string $resource = ModeOfPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
