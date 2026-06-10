<?php

namespace App\Filament\Resources\AdvisoryResource\Pages;

use App\Filament\Resources\AdvisoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdvisories extends ListRecords
{
    protected static string $resource = AdvisoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Post Advisory')
                ->color('success'),
        ];
    }
}
