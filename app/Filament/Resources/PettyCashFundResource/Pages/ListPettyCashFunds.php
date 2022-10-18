<?php

namespace App\Filament\Resources\PettyCashFundResource\Pages;

use App\Filament\Resources\PettyCashFundResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPettyCashFunds extends ListRecords
{
    protected static string $resource = PettyCashFundResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->color('success')
                ->label('New Petty Cash Fund'),
        ];
    }
}
