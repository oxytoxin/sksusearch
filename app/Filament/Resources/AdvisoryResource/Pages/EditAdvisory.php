<?php

namespace App\Filament\Resources\AdvisoryResource\Pages;

use App\Filament\Resources\AdvisoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvisory extends EditRecord
{
    protected static string $resource = AdvisoryResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['file_name'] = $data['file_path'] ? basename($data['file_path']) : null;

        return $data;
    }

    protected function getHeaderActions(): array
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
