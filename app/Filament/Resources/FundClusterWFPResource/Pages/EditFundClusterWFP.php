<?php

namespace App\Filament\Resources\FundClusterWFPResource\Pages;

use App\Filament\Resources\FundClusterWFPResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFundClusterWFP extends EditRecord
{
    protected static string $resource = FundClusterWFPResource::class;

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
