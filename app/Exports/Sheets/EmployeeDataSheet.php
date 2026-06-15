<?php

namespace App\Exports\Sheets;

use App\Models\Campus;
use App\Models\Office;
use App\Models\Position;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeDataSheet implements FromArray, WithTitle, WithEvents, WithStyles, ShouldAutoSize
{
    public function title(): string
    {
        return 'Employee Data';
    }

    public function array(): array
    {
        return [
            [
                'first_name',
                'last_name',
                'full_name',
                'email',
                'address',
                'birthday',
                'contact_number',
                'campus',
                'office',
                'position',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4472C4'],
                ],
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $campusCount = Campus::count();
                $officeCount = Office::count();
                $positionCount = Position::count();

                $lastRow = 500;

                // Campus dropdown (column H)
                $campusRange = 'Reference!$A$2:$A$' . ($campusCount + 1);
                $this->applyDropdown($sheet, 'H', 2, $lastRow, $campusRange);

                // Office dropdown (column I)
                $officeRange = 'Reference!$B$2:$B$' . ($officeCount + 1);
                $this->applyDropdown($sheet, 'I', 2, $lastRow, $officeRange);

                // Position dropdown (column J)
                $positionRange = 'Reference!$C$2:$C$' . ($positionCount + 1);
                $this->applyDropdown($sheet, 'J', 2, $lastRow, $positionRange);

                // Freeze header row
                $sheet->freezePane('A2');
            },
        ];
    }

    private function applyDropdown(Worksheet $sheet, string $col, int $startRow, int $endRow, string $formula): void
    {
        for ($row = $startRow; $row <= $endRow; $row++) {
            $validation = $sheet->getCell($col . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Invalid value');
            $validation->setError('Please select a value from the dropdown list.');
            $validation->setFormula1($formula);
        }
    }
}
