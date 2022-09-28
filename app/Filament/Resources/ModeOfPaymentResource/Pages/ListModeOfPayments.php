<?php

namespace App\Filament\Resources\ModeOfPaymentResource\Pages;

use App\Filament\Resources\ModeOfPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModeOfPayments extends ListRecords
{
    protected static string $resource = ModeOfPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Mode Of Payment')
            ->color('success'),
        ];
    }
}
