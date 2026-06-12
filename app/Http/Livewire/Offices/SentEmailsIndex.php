<?php

namespace App\Http\Livewire\Offices;

use App\Models\EmailLog;
use Livewire\Component;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

/**
 * "Sent Emails" log for the Accounting Office.
 *
 * Fulfils the ICU/Accounting request: "Accounting Unit must have access to all
 * sent emails." Reads the email_logs table (written by SendEmailJob) — every
 * outbound email (return notices, etc.) with its status, recipient, and body.
 *
 * Accounting-only (office_group_id == 2), guarded in mount() — same pattern as
 * OfficeLiquidationReportsIndex.
 */
class SentEmailsIndex extends Component implements HasTable
{
    use InteractsWithTable;

    public function mount()
    {
        if (! in_array(auth()->user()->employee_information?->office?->office_group_id, [2])) {
            abort(403, 'You are not allowed to access this page.');
        }
    }

    protected function getTableQuery()
    {
        return EmailLog::query()
            ->with(['sender.employee_information', 'user.employee_information'])
            ->latest();
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('created_at')->label('Date')->dateTime('M d, Y g:i A')->sortable(),
            TextColumn::make('recipient_email')->label('Recipient')->searchable()->wrap(),
            TextColumn::make('subject')->label('Subject')->searchable()->wrap()->limit(60),
            TextColumn::make('context')->label('Context')->formatStateUsing(fn ($state) => $state ? ucwords(str_replace('_', ' ', $state)) : '—')->wrap(),
            BadgeColumn::make('status')->label('Status')->colors([
                'success' => 'sent',
                'danger' => 'failed',
                'warning' => 'pending',
            ])->formatStateUsing(fn ($state) => ucfirst($state)),
            TextColumn::make('sender.employee_information.full_name')->label('Sent By')->wrap()->default('System'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('status')->options([
                'sent' => 'Sent',
                'failed' => 'Failed',
                'pending' => 'Pending',
            ])->label('Status'),
            SelectFilter::make('context')
                ->options(fn () => EmailLog::query()
                    ->whereNotNull('context')
                    ->distinct()
                    ->orderBy('context')
                    ->pluck('context', 'context')
                    ->map(fn ($c) => ucwords(str_replace('_', ' ', $c)))
                    ->toArray())
                ->label('Context'),
            Filter::make('created_at')
                ->form([
                    Grid::make(2)->schema([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ]),
                ])
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['from'] ?? null) {
                        $indicators['from'] = 'From ' . Carbon::parse($data['from'])->toFormattedDateString();
                    }
                    if ($data['until'] ?? null) {
                        $indicators['until'] = 'Until ' . Carbon::parse($data['until'])->toFormattedDateString();
                    }
                    return $indicators;
                })
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['from'], fn (Builder $q, $date): Builder => $q->whereDate('created_at', '>=', $date))
                        ->when($data['until'], fn (Builder $q, $date): Builder => $q->whereDate('created_at', '<=', $date));
                }),
        ];
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getTableActions(): array
    {
        return [
            ViewAction::make('view')
                ->label('View')
                ->icon('ri-mail-open-line')
                ->modalHeading('Email Details')
                ->modalWidth('3xl')
                ->modalContent(fn ($record) => view('components.emails.sent-email-detail', [
                    'record' => $record,
                ])),
        ];
    }

    public function render()
    {
        return view('livewire.offices.sent-emails-index');
    }
}
