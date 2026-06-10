<?php

namespace App\Filament\Resources\AdvisoryResource\Pages;

use App\Filament\Resources\AdvisoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdvisory extends CreateRecord
{
    protected static string $resource = AdvisoryResource::class;

    protected static ?string $title = 'Post Advisory';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['file_name'] = $data['file_path'] ? basename($data['file_path']) : null;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected static function canCreateAnother(): bool
    {
        return false;
    }
}
