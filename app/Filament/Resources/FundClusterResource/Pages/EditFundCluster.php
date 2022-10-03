<?php

namespace App\Filament\Resources\FundClusterResource\Pages;

use App\Filament\Resources\FundClusterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFundCluster extends EditRecord
{
    protected static string $resource = FundClusterResource::class;

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
