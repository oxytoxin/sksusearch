<?php

namespace App\Filament\Resources\EmployeeInformationResource\Pages;

use App\Filament\Resources\EmployeeInformationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeInformation extends ListRecords
{
    protected static string $resource = EmployeeInformationResource::class;

    protected function getTitle(): string
    {
        return 'Employee Information';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->color('success')
                ->label('New Employee'),
        ];
    }
}
