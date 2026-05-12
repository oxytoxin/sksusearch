<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashAdvanceAgingExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(private Builder $query)
    {
    }

    public function query()
    {
        return $this->query
            ->with([
                'disbursementVoucher.user.employee_information.office',
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
            'DV No.',
            'Tracking No.',
            'Owner',
            'Office',
            'Date Granted',
            'Amount (PHP)',
            'Liquidation Deadline',
            'Days Overdue',
            'Bucket',
            'Stage',
        ];
    }

    public function map($step): array
    {
        $dv = $step->disbursementVoucher;
        $days = (int) ($step->days_overdue ?? 0);

        return [
            $dv?->dv_number,
            $dv?->tracking_number,
            $dv?->user?->name,
            $dv?->user?->employee_information?->office?->name,
            $dv?->created_at ? Carbon::parse($dv->created_at)->format('M d, Y') : '',
            $dv?->total_sum ?? 0,
            $step->liquidation_period_end_date
                ? Carbon::parse($step->liquidation_period_end_date)->format('M d, Y')
                : '',
            $days,
            $this->bucketFor($days),
            $this->stageFor((int) $step->step),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function bucketFor(int $days): string
    {
        if ($days <= 30) {
            return '0-30';
        }
        if ($days <= 60) {
            return '31-60';
        }
        if ($days <= 90) {
            return '61-90';
        }

        return '90+';
    }

    private function stageFor(int $step): string
    {
        return match ($step) {
            1 => 'Initial',
            2 => 'FMR',
            3 => 'FMD',
            4 => 'SCO',
            5 => 'Endorsed',
            6 => 'FD',
            7 => 'FD Uploaded',
            default => 'N/A',
        };
    }
}
