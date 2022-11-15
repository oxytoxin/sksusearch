<?php

namespace App\Filament\Resources\ArchiveDocumentResource\Pages;

use App\Filament\Resources\ArchiveDocumentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArchiveDocuments extends ListRecords
{
    protected static string $resource = ArchiveDocumentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Document Type')
                ->color('success'),
        ];
    }
}
