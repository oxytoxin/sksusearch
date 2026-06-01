<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashAdvanceAgingExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents, ShouldAutoSize
{
    private array $bucketTotals = [
        '30'     => 0.0,
        '31-90'  => 0.0,
        '91-365' => 0.0,
        '1-2y'   => 0.0,
        '2y+'    => 0.0,
    ];

    private float $grandTotal = 0.0;

    private int $rowCount = 0;

    public function __construct(
        private Builder $query,
        private ?Carbon $asOfDate = null,
    ) {
        $this->asOfDate = $asOfDate ?? today();
    }

    public function query()
    {
        return $this->query->with([
            'disbursementVoucher.user.employee_information.office',
            'disbursementVoucher.user.employee_information.position',
            'disbursementVoucher.disbursement_voucher_particulars',
        ]);
    }

    public function title(): string
    {
        return 'Aging of Cash Advances';
    }

    public function headings(): array
    {
        return [
            '#',
            'Name of Accountable Officer',
            'Position',
            'Office',
            'Purpose',
            'Date Granted',
            'Check No.',
            'Amount Granted',
            'Days Overdue',
            '30',
            '31-90',
            '91-365',
            '>1 Year',
            '>2 Years',
        ];
    }

    public function map($step): array
    {
        $this->rowCount++;

        $dv       = $step->disbursementVoucher;
        $owner    = $dv?->user;
        $info     = $owner?->employee_information;
        $first    = $dv?->disbursement_voucher_particulars?->first();
        $amount   = (float) ($dv?->totalSum ?? 0);
        $days     = (int) ($step->days_overdue ?? 0);
        $bucket   = $this->bucketFor($days);

        $this->bucketTotals[$bucket] += $amount;
        $this->grandTotal += $amount;

        return [
            $this->rowCount,
            $owner?->name ?? '',
            $info?->position?->description ?? '',
            $info?->office?->name ?? '',
            $first?->purpose ?? ($dv?->payee ?? ''),
            $dv?->created_at ? Carbon::parse($dv->created_at)->format('M d, Y') : '',
            $dv?->cheque_number ?? '',
            $amount,
            $days,
            $bucket === '30'     ? $amount : null,
            $bucket === '31-90'  ? $amount : null,
            $bucket === '91-365' ? $amount : null,
            $bucket === '1-2y'   ? $amount : null,
            $bucket === '2y+'    ? $amount : null,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Append grand-totals row after the last data row.
                $totalsRow = $this->rowCount + 2; // headings on row 1, data starts row 2

                $sheet->setCellValue('A'.$totalsRow, 'GRAND TOTAL');
                $sheet->mergeCells('A'.$totalsRow.':G'.$totalsRow);
                $sheet->setCellValue('H'.$totalsRow, $this->grandTotal);
                $sheet->setCellValue('J'.$totalsRow, $this->bucketTotals['30']);
                $sheet->setCellValue('K'.$totalsRow, $this->bucketTotals['31-90']);
                $sheet->setCellValue('L'.$totalsRow, $this->bucketTotals['91-365']);
                $sheet->setCellValue('M'.$totalsRow, $this->bucketTotals['1-2y']);
                $sheet->setCellValue('N'.$totalsRow, $this->bucketTotals['2y+']);

                $sheet->getStyle('A'.$totalsRow.':N'.$totalsRow)->getFont()->setBold(true);
                $sheet->getStyle('A'.$totalsRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Number format on amount columns
                $sheet->getStyle('H2:H'.$totalsRow)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('J2:N'.$totalsRow)->getNumberFormat()->setFormatCode('#,##0.00');
            },
        ];
    }

    private function bucketFor(int $days): string
    {
        if ($days <= 30)  return '30';
        if ($days <= 90)  return '31-90';
        if ($days <= 365) return '91-365';
        if ($days <= 730) return '1-2y';

        return '2y+';
    }
}
