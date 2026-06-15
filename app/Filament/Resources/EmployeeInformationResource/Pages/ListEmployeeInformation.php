<?php

namespace App\Filament\Resources\EmployeeInformationResource\Pages;

use App\Exports\EmployeeTemplateExport;
use App\Filament\Resources\EmployeeInformationResource;
use App\Imports\EmployeeInformationImport;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

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
            Actions\Action::make('import')
                ->label('Import Employees')
                ->icon('heroicon-o-upload')
                ->color('warning')
                ->form([
                    FileUpload::make('file')
                        ->label('Excel File (.xlsx)')
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $path = storage_path('app/' . $data['file']);

                    $import = new EmployeeInformationImport();
                    Excel::import($import, $path);

                    // Clean up uploaded file
                    @unlink($path);

                    $message = "Imported: {$import->imported}";
                    if ($import->skipped > 0) {
                        $message .= ", Skipped: {$import->skipped}";
                    }

                    if (!empty($import->errors)) {
                        Notification::make()
                            ->title('Import completed with errors')
                            ->body($message . "\n" . implode("\n", array_slice($import->errors, 0, 10)))
                            ->warning()
                            ->persistent()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Import successful')
                            ->body($message)
                            ->success()
                            ->send();
                    }
                }),
            Actions\Action::make('export_template')
                ->label('Download Template')
                ->icon('heroicon-o-download')
                ->color('primary')
                ->action(function () {
                    return Excel::download(new EmployeeTemplateExport(), 'employee_template.xlsx');
                }),
            Actions\CreateAction::make()
                ->color('success')
                ->label('New Employee'),
        ];
    }
}
