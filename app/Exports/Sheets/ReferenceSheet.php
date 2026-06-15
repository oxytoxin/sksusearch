<?php

namespace App\Exports\Sheets;

use App\Models\Campus;
use App\Models\Office;
use App\Models\Position;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ReferenceSheet implements FromArray, WithTitle, WithEvents
{
    public function title(): string
    {
        return 'Reference';
    }

    public function array(): array
    {
        $campuses = Campus::orderBy('name')->pluck('name')->toArray();
        $offices = Office::with('campus')
            ->orderBy('campus_id')
            ->orderBy('name')
            ->get()
            ->map(fn ($o) => $o->name . ' (' . ($o->campus->name ?? '') . ')')
            ->toArray();
        $positions = Position::orderBy('description')->pluck('description')->toArray();

        $maxRows = max(count($campuses), count($offices), count($positions));

        $rows = [['campus', 'office', 'position']];
        for ($i = 0; $i < $maxRows; $i++) {
            $rows[] = [
                $campuses[$i] ?? '',
                $offices[$i] ?? '',
                $positions[$i] ?? '',
            ];
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getSheetView()->setZoomScale(100);
            },
        ];
    }
}
