<?php

namespace App\Filament\Resources\ArchiveDocumentResource\Pages;

use App\Filament\Resources\ArchiveDocumentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArchiveDocument extends EditRecord
{
    protected static string $resource = ArchiveDocumentResource::class;

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
