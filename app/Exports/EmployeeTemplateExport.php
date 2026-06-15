<?php

namespace App\Exports;

use App\Exports\Sheets\EmployeeDataSheet;
use App\Exports\Sheets\ReferenceSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EmployeeTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new EmployeeDataSheet(),
            new ReferenceSheet(),
        ];
    }
}
