<?php

namespace App\Filament\Resources\FundClusterResource\Pages;

use App\Filament\Resources\FundClusterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFundCluster extends EditRecord
{
    protected static string $resource = FundClusterResource::class;

    protected function getHeaderActions(): array
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
