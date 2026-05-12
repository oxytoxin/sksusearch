<?php

namespace App\Http\Livewire\Reports;

use App\Exports\CashAdvanceAgingExport;
use App\Models\CaReminderStep;
use App\Models\Office;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class CashAdvanceAging extends Component implements HasTable
{
    use InteractsWithTable;

    public function mount(): void
    {
        abort_unless($this->userIsAuthorized(), 403);
    }

    protected function userIsAuthorized(): bool
    {
        $info = Auth::user()?->employee_information;
        if (!$info) {
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

    protected function daysOverdueExpression(): string
    {
        return 'GREATEST(DATEDIFF(CURDATE(), ca_reminder_steps.liquidation_period_end_date), 0)';
    }

    protected function baseQuery(): Builder
    {
        return CaReminderStep::query()
            ->whereHas('disbursementVoucher', function ($q) {
                $q->whereDoesntHave('liquidation_report', fn ($lr) => $lr->whereNull('cancelled_at'))
                    ->whereRelation('voucher_subtype', 'voucher_type_id', 1)
                    ->where('voucher_subtype_id', '!=', 69)
                    ->whereNotNull('cheque_number');
            })
            ->whereNotNull('liquidation_period_end_date')
            ->where('liquidation_period_end_date', '<', today());
    }

    protected function getTableQuery(): Builder|Relation
    {
        return $this->baseQuery()
            ->select('ca_reminder_steps.*')
            ->selectRaw($this->daysOverdueExpression().' as days_overdue')
            ->with([
                'disbursementVoucher.user.employee_information.office',
                'disbursementVoucher.disbursement_voucher_particulars',
            ]);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('disbursementVoucher.dv_number')
                ->label('DV No.')
                ->searchable()
                ->sortable(),

            TextColumn::make('disbursementVoucher.tracking_number')
                ->label('Tracking No.')
                ->searchable()
                ->sortable(),

            TextColumn::make('disbursementVoucher.user.name')
                ->label('Owner')
                ->searchable()
                ->sortable()
                ->wrap(),

            TextColumn::make('disbursementVoucher.user.employee_information.office.name')
                ->label('Office')
                ->searchable()
                ->sortable()
                ->wrap(),

            TextColumn::make('disbursementVoucher.created_at')
                ->label('Date Granted')
                ->date('M d, Y')
                ->sortable(),

            TextColumn::make('disbursementVoucher.totalSum')
                ->label('Amount')
                ->money('PHP', true)
                ->alignRight(),

            TextColumn::make('liquidation_period_end_date')
                ->label('Liquidation Deadline')
                ->date('M d, Y')
                ->sortable(),

            TextColumn::make('days_overdue')
                ->label('Days Overdue')
                ->alignRight()
                ->sortable(query: function (Builder $query, string $direction): Builder {
                    return $query->orderByRaw($this->daysOverdueExpression().' '.$direction);
                }),

            BadgeColumn::make('bucket')
                ->label('Bucket')
                ->getStateUsing(fn ($record) => $this->bucketFor((int) ($record->days_overdue ?? 0)))
                ->colors([
                    'primary' => static fn ($state): bool => $state === '0-30',
                    'warning' => static fn ($state): bool => $state === '31-60',
                    'secondary' => static fn ($state): bool => $state === '61-90',
                    'danger' => static fn ($state): bool => $state === '90+',
                ]),

            BadgeColumn::make('current_stage')
                ->label('Stage')
                ->getStateUsing(fn ($record) => $this->stageFor((int) $record->step))
                ->colors([
                    'primary' => static fn ($state): bool => $state === 'Initial',
                    'warning' => static fn ($state): bool => in_array($state, ['FMR', 'FMD']),
                    'secondary' => static fn ($state): bool => in_array($state, ['SCO', 'Endorsed']),
                    'danger' => static fn ($state): bool => in_array($state, ['FD', 'FD Uploaded']),
                ]),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('bucket')
                ->label('Aging Bucket')
                ->options([
                    '0-30' => '0 – 30 days',
                    '31-60' => '31 – 60 days',
                    '61-90' => '61 – 90 days',
                    '90+' => '90+ days',
                ])
                ->query(function (Builder $query, $state) {
                    if (blank($state)) {
                        return $query;
                    }
                    $expr = $this->daysOverdueExpression();

                    return match ($state) {
                        '0-30'  => $query->whereRaw("$expr BETWEEN 0 AND 30"),
                        '31-60' => $query->whereRaw("$expr BETWEEN 31 AND 60"),
                        '61-90' => $query->whereRaw("$expr BETWEEN 61 AND 90"),
                        '90+'   => $query->whereRaw("$expr > 90"),
                        default => $query,
                    };
                }),

            SelectFilter::make('office_id')
                ->label('Office')
                ->searchable()
                ->options(Office::orderBy('name')->pluck('name', 'id'))
                ->query(function (Builder $query, $state) {
                    if (blank($state)) {
                        return $query;
                    }

                    return $query->whereHas('disbursementVoucher.user.employee_information', function ($q) use ($state) {
                        $q->where('office_id', $state);
                    });
                }),
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'days_overdue';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No unliquidated cash advances';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Nothing matches the current filters.';
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-document-text';
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export Excel')
                ->icon('heroicon-s-document-download')
                ->color('primary')
                ->button()
                ->action(function () {
                    $filename = 'cash-advance-aging-'.now()->format('Ymd-His').'.xlsx';

                    return Excel::download(
                        new CashAdvanceAgingExport($this->getFilteredTableQuery()),
                        $filename,
                    );
                }),
        ];
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

    protected function stageFor(int $step): string
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

    public function getBucketCountsProperty(): array
    {
        $expr = $this->daysOverdueExpression();

        $rows = $this->baseQuery()
            ->selectRaw("
                CASE
                    WHEN $expr <= 30 THEN '0-30'
                    WHEN $expr <= 60 THEN '31-60'
                    WHEN $expr <= 90 THEN '61-90'
                    ELSE '90+'
                END as bucket,
                COUNT(*) as cnt,
                SUM(COALESCE((
                    SELECT SUM(final_amount)
                    FROM disbursement_voucher_particulars
                    WHERE disbursement_voucher_id = ca_reminder_steps.disbursement_voucher_id
                ), 0)) as total
            ")
            ->groupBy('bucket')
            ->get();

        $summary = [
            '0-30'  => ['count' => 0, 'total' => 0.0],
            '31-60' => ['count' => 0, 'total' => 0.0],
            '61-90' => ['count' => 0, 'total' => 0.0],
            '90+'   => ['count' => 0, 'total' => 0.0],
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

    public function render()
    {
        return view('livewire.reports.cash-advance-aging', [
            'bucketCounts' => $this->bucket_counts,
        ]);
    }
}
