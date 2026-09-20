<?php

namespace App\Filament\Resources\SupplementalQuarterResource\Pages;

use App\Filament\Resources\SupplementalQuarterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupplementalQuarter extends EditRecord
{
    protected static string $resource = SupplementalQuarterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }
}
