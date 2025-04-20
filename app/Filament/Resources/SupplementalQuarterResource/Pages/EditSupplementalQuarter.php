<?php

namespace App\Filament\Resources\SupplementalQuarterResource\Pages;

use App\Filament\Resources\SupplementalQuarterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupplementalQuarter extends EditRecord
{
    protected static string $resource = SupplementalQuarterResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }
}
