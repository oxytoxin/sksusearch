<?php

namespace App\Filament\Resources\ArchiveDocumentResource\Pages;

use App\Filament\Resources\ArchiveDocumentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArchiveDocument extends CreateRecord
{
    protected static string $resource = ArchiveDocumentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
