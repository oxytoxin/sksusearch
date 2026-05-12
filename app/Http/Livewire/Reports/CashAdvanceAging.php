<?php

namespace App\Http\Livewire\Reports;

use App\Models\CaReminderStep;
use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CashAdvanceAging extends Component
{
    public string $bucket = 'all';

    public $officeId = null;

    public string $search = '';

    protected $queryString = ['bucket', 'officeId', 'search'];

    public function mount(): void
    {
        abort_unless($this->userIsAuthorized(), 403);
    }

    public function updatingBucket(): void
    {
        $this->resetPage();
    }

    public function updatingOfficeId(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function resetPage(): void
    {
        // No pagination yet; placeholder kept for consistency if added later.
    }

    protected function userIsAuthorized(): bool
    {
        $info = Auth::user()?->employee_information;
        if (!$info) {
            return false;
        }

        return $this->isAccountant($info) || $this->isFinance($info) || $this->isPresident($info);
    }

    protected function isAccountant($info): bool
    {
        return $info->office_id == 3 && $info->position_id == 15;
    }

    protected function isFinance($info): bool
    {
        return $info->office_id == 25 && in_array($info->position_id, [12, 38]);
    }

    protected function isPresident($info): bool
    {
        return $info->office_id == 51 && $info->position_id == 34;
    }

    protected function baseQuery()
    {
        return CaReminderStep::query()
            ->whereHas('disbursement_voucher', function ($q) {
                $q->whereDoesntHave('liquidation_report', fn ($lr) => $lr->whereNull('cancelled_at'))
                    ->whereRelation('voucher_subtype', 'voucher_type_id', 1)
                    ->where('voucher_subtype_id', '!=', 69)
                    ->whereNotNull('cheque_number');
            })
            ->whereNotNull('liquidation_period_end_date')
            ->with([
                'disbursement_voucher.user.employee_information.office',
                'disbursement_voucher.disbursement_voucher_particulars',
            ]);
    }

    protected function buildRows()
    {
        $today = Carbon::today();

        $rows = $this->baseQuery()->get()->map(function ($step) use ($today) {
            $dv = $step->disbursement_voucher;
            $deadline = $step->liquidation_period_end_date
                ? Carbon::parse($step->liquidation_period_end_date)
                : null;

            $daysOverdue = $deadline && $deadline->lt($today)
                ? $deadline->diffInDays($today)
                : 0;

            return (object) [
                'id' => $step->id,
                'dv' => $dv,
                'step' => $step,
                'dv_number' => $dv?->dv_number,
                'tracking_number' => $dv?->tracking_number,
                'owner_name' => $dv?->user?->name,
                'office_id' => $dv?->user?->employee_information?->office_id,
                'office_name' => $dv?->user?->employee_information?->office?->name,
                'date_granted' => $dv?->created_at,
                'amount' => $dv?->total_sum,
                'liquidation_deadline' => $deadline,
                'days_overdue' => $daysOverdue,
                'bucket' => $this->bucketFor($daysOverdue),
                'current_stage' => $this->stageFor($step),
            ];
        });

        return $rows;
    }

    protected function bucketFor(int $days): string
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

    protected function stageFor($step): string
    {
        return match ((int) $step->step) {
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

    public function getAllRowsProperty()
    {
        return $this->buildRows();
    }

    public function getRowsProperty()
    {
        $rows = $this->all_rows;
        $bucket = $this->bucket;
        $officeId = $this->officeId;
        $search = trim(strtolower($this->search));

        return $rows->filter(function ($row) use ($bucket, $officeId, $search) {
            if ($bucket !== 'all' && $row->bucket !== $bucket) {
                return false;
            }
            if ($officeId && (int) $row->office_id !== (int) $officeId) {
                return false;
            }
            if ($search !== '') {
                $haystack = strtolower(($row->dv_number ?? '') . ' ' . ($row->tracking_number ?? '') . ' ' . ($row->owner_name ?? ''));
                if (!str_contains($haystack, $search)) {
                    return false;
                }
            }

            return true;
        })->values();
    }

    public function getBucketCountsProperty(): array
    {
        $rows = $this->all_rows;

        $buckets = ['0-30', '31-60', '61-90', '90+'];
        $summary = [];
        foreach ($buckets as $b) {
            $matching = $rows->where('bucket', $b);
            $summary[$b] = [
                'count' => $matching->count(),
                'total' => $matching->sum('amount'),
            ];
        }

        return $summary;
    }

    public function getOfficesProperty()
    {
        return Office::orderBy('name')->get(['id', 'name']);
    }

    public function render()
    {
        return view('livewire.reports.cash-advance-aging', [
            'rows' => $this->rows,
            'bucketCounts' => $this->bucket_counts,
            'offices' => $this->offices,
        ]);
    }
}
