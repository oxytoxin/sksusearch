<?php

namespace App\Console\Commands;

use App\Exports\EmployeeTemplateExport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class GenerateEmployeeTemplate extends Command
{
    protected $signature = 'app:generate-employee-template';

    protected $description = 'Generate an Excel template for bulk employee data entry with dropdown validation';

    public function handle(): int
    {
        $filename = 'employee_template.xlsx';

        Excel::store(new EmployeeTemplateExport(), $filename, 'local');

        $path = storage_path('app/' . $filename);
        $this->info("Employee template generated: {$path}");

        return self::SUCCESS;
    }
}
