<?php

namespace App\Filament\Resources\PettyCashFundResource\Pages;

use App\Filament\Resources\PettyCashFundResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPettyCashFund extends EditRecord
{
    protected static string $resource = PettyCashFundResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
