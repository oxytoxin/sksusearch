<?php

namespace App\Http\Livewire\Reports;

use App\Exports\CashAdvanceAgingExport;
use App\Models\CaReminderStep;
use App\Models\FundCluster;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class CashAdvanceAging extends Component
{
    /**
     * Hard cap on rows rendered to the page / printed.
     * The Excel export streams via FromQuery and is NOT capped.
     */
    public const ROW_RENDER_LIMIT = 1000;

    public string $asOfDate = '';

    public ?string $bucketFilter = null;

    public ?int $fundClusterId = null;

    public string $search = '';

    public int $totalMatchingCount = 0;

    protected $queryString = [
        'asOfDate'      => ['except' => ''],
        'bucketFilter'  => ['except' => null],
        'fundClusterId' => ['except' => null],
        'search'        => ['except' => ''],
    ];

    public function mount(): void
    {
        abort_unless($this->userIsAuthorized(), 403);

        if (blank($this->asOfDate)) {
            $this->asOfDate = today()->format('Y-m-d');
        }
    }

    public function updatedAsOfDate(): void
    {
        // Just re-render; computed props will recompute.
    }

    public function updatedBucketFilter(): void
    {
        // No-op; reactive.
    }

    public function updatedSearch(): void
    {
        // No-op; reactive.
    }

    public function updatedFundClusterId(): void
    {
        // No-op; reactive.
    }

    public function setBucket(?string $bucket): void
    {
        $this->bucketFilter = $bucket;
    }

    public function clearFilters(): void
    {
        $this->bucketFilter = null;
        $this->fundClusterId = null;
        $this->search = '';
        $this->asOfDate = today()->format('Y-m-d');
    }

    public function exportExcel()
    {
        $filename = 'cash-advance-aging-'.now()->format('Ymd-His').'.xlsx';

        return Excel::download(
            new CashAdvanceAgingExport(
                $this->filteredQuery(),
                $this->asOfDateCarbon(),
            ),
            $filename,
        );
    }

    protected function userIsAuthorized(): bool
    {
        $info = Auth::user()?->employee_information;
        if (! $info) {
            return false;
        }

        return $this->isAccountant($info)
            || $this->isFinance($info)
            || $this->isPresident($info)
            || $this->isAuditor($info);
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

    protected function isAuditor($info): bool
    {
        return $info->office_id == 61 && $info->position_id == 31;
    }

    protected function asOfDateCarbon(): Carbon
    {
        if (blank($this->asOfDate)) {
            return today();
        }

        try {
            return Carbon::parse($this->asOfDate)->startOfDay();
        } catch (\Throwable $e) {
            return today();
        }
    }

    protected function daysOverdueExpression(): string
    {
        // Use parameter binding-friendly literal date built in PHP.
        $date = $this->asOfDateCarbon()->toDateString();

        return "GREATEST(DATEDIFF('{$date}', ca_reminder_steps.liquidation_period_end_date), 0)";
    }

    protected function baseQuery(): Builder
    {
        $asOf = $this->asOfDateCarbon();
        $fundId = $this->fundClusterId;

        return CaReminderStep::query()
            ->whereHas('disbursementVoucher', function ($q) use ($fundId) {
                $q->whereDoesntHave('liquidation_report', fn ($lr) => $lr->whereNull('cancelled_at'))
                    ->whereRelation('voucher_subtype', 'voucher_type_id', 1)
                    ->where('voucher_subtype_id', '!=', 69)
                    ->whereNotNull('cheque_number');

                if (filled($fundId)) {
                    $q->where('fund_cluster_id', $fundId);
                }
            })
            ->whereNotNull('liquidation_period_end_date')
            ->whereDate('liquidation_period_end_date', '<', $asOf);
    }

    protected function filteredQuery(): Builder
    {
        $query = $this->baseQuery()
            ->select('ca_reminder_steps.*')
            ->selectRaw($this->daysOverdueExpression().' as days_overdue')
            ->with([
                'disbursementVoucher.user.employee_information.office',
                'disbursementVoucher.user.employee_information.position',
                'disbursementVoucher.disbursement_voucher_particulars',
            ])
            ->orderByRaw($this->daysOverdueExpression().' desc');

        if (filled($this->search)) {
            $needle = '%'.trim($this->search).'%';
            $query->whereHas('disbursementVoucher', function ($q) use ($needle) {
                $q->where('dv_number', 'like', $needle)
                    ->orWhere('tracking_number', 'like', $needle)
                    ->orWhere('payee', 'like', $needle)
                    ->orWhere('cheque_number', 'like', $needle)
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $needle))
                    ->orWhereHas(
                        'user.employee_information.office',
                        fn ($o) => $o->where('name', 'like', $needle),
                    );
            });
        }

        if (filled($this->bucketFilter)) {
            $expr = $this->daysOverdueExpression();
            $query->whereRaw(match ($this->bucketFilter) {
                '30'      => "$expr BETWEEN 0 AND 30",
                '31-90'   => "$expr BETWEEN 31 AND 90",
                '91-365'  => "$expr BETWEEN 91 AND 365",
                '1-2y'    => "$expr BETWEEN 366 AND 730",
                '2y+'     => "$expr > 730",
                default   => '1=1',
            });
        }

        return $query;
    }

    public function getRowsProperty(): Collection
    {
        $base = $this->filteredQuery();

        $this->totalMatchingCount = (clone $base)->toBase()->getCountForPagination();

        return $base->limit(self::ROW_RENDER_LIMIT)->get();
    }

    public function getBucketCountsProperty(): array
    {
        $expr = $this->daysOverdueExpression();

        // Aggregate particulars in a single derived table (JOIN) instead of a
        // correlated subquery so we scan disbursement_voucher_particulars once.
        $particularsSum = DB::table('disbursement_voucher_particulars')
            ->selectRaw('disbursement_voucher_id, SUM(final_amount) as dv_total')
            ->groupBy('disbursement_voucher_id');

        $rows = $this->baseQuery()
            ->leftJoinSub($particularsSum, 'dvp_sum', function ($join) {
                $join->on('dvp_sum.disbursement_voucher_id', '=', 'ca_reminder_steps.disbursement_voucher_id');
            })
            ->selectRaw("
                CASE
                    WHEN $expr <= 30  THEN '30'
                    WHEN $expr <= 90  THEN '31-90'
                    WHEN $expr <= 365 THEN '91-365'
                    WHEN $expr <= 730 THEN '1-2y'
                    ELSE '2y+'
                END as bucket,
                COUNT(*) as cnt,
                COALESCE(SUM(dvp_sum.dv_total), 0) as total
            ")
            ->groupBy('bucket')
            ->get();

        $summary = [
            '30'     => ['count' => 0, 'total' => 0.0],
            '31-90'  => ['count' => 0, 'total' => 0.0],
            '91-365' => ['count' => 0, 'total' => 0.0],
            '1-2y'   => ['count' => 0, 'total' => 0.0],
            '2y+'    => ['count' => 0, 'total' => 0.0],
        ];

        foreach ($rows as $r) {
            if (isset($summary[$r->bucket])) {
                $summary[$r->bucket] = [
                    'count' => (int) $r->cnt,
                    'total' => (float) $r->total,
                ];
            }
        }

        return $summary;
    }

    public function getGrandTotalProperty(): float
    {
        return (float) $this->rows->sum(
            fn ($step) => (float) ($step->disbursementVoucher?->totalSum ?? 0),
        );
    }

    public function getBucketTotalsProperty(): array
    {
        $totals = [
            '30'     => 0.0,
            '31-90'  => 0.0,
            '91-365' => 0.0,
            '1-2y'   => 0.0,
            '2y+'    => 0.0,
        ];

        foreach ($this->rows as $row) {
            $bucket = $this->bucketFor((int) ($row->days_overdue ?? 0));
            $totals[$bucket] += (float) ($row->disbursementVoucher?->totalSum ?? 0);
        }

        return $totals;
    }

    public function bucketFor(int $days): string
    {
        if ($days <= 30)  return '30';
        if ($days <= 90)  return '31-90';
        if ($days <= 365) return '91-365';
        if ($days <= 730) return '1-2y';

        return '2y+';
    }

    public function getFundClustersProperty()
    {
        return FundCluster::orderBy('name')->get(['id', 'name']);
    }

    public function bucketLabel(string $key): string
    {
        return match ($key) {
            '30'     => '30 days',
            '31-90'  => '31 – 90 days',
            '91-365' => '91 – 365 days',
            '1-2y'   => 'Over 1 year',
            '2y+'    => 'Over 2 years',
            default  => $key,
        };
    }

    public function render()
    {
        $rows = $this->rows; // also populates totalMatchingCount

        return view('livewire.reports.cash-advance-aging', [
            'rows'               => $rows,
            'bucketCounts'       => $this->bucketCounts,
            'bucketTotals'       => $this->bucketTotals,
            'grandTotal'         => $this->grandTotal,
            'asOfDisplay'        => $this->asOfDateCarbon()->format('F j, Y'),
            'totalMatchingCount' => $this->totalMatchingCount,
            'rowLimit'           => self::ROW_RENDER_LIMIT,
            'fundClusters'       => $this->fundClusters,
        ]);
    }
}
