<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use App\Models\CaReminderStepHistory;
use App\Models\DisbursementVoucher;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Livewire\Component;

class DisbursementVoucherNotices extends Component implements HasTable
{
    use InteractsWithTable;

    public DisbursementVoucher $disbursement_voucher;

    public function mount(DisbursementVoucher $disbursement_voucher)
    {
        $this->disbursement_voucher = $disbursement_voucher;
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.disbursement-voucher-notices');
    }

    protected function getTableQuery()
    {
        return CaReminderStepHistory::query()
            ->whereHas('caReminderStep', function ($q) {
                $q->where('disbursement_voucher_id', $this->disbursement_voucher->id);
            })
            ->with(['caReminderStep.disbursementVoucher'])
            ->latest();
    }

    protected function getTableActions(): array
    {
        return [
            ActionGroup::make([

                ViewAction::make('view')
                    ->label('Preview DV')
                    ->modalContent(
                        fn ($record) => view('components.disbursement_vouchers.disbursement_voucher_view_no_layout', [
                            'disbursement_voucher' => $record->caReminderStep->disbursement_voucher,
                        ])
                    )
                    ->modalWidth('4xl')
                    ->button()
                    ->color('primary')
                    ->icon('heroicon-o-eye'),

                // 🔹 Show FMR only if record type == 'FMR'
                ViewAction::make('FMR')
                    ->label('View FMR')
                    ->url(fn ($record) => route('print.formal-management-reminder', [
                        'record' => $record->caReminderStep->disbursementVoucher,
                    ]))
                    ->button()
                    ->color('primary')
                    ->icon('heroicon-o-document-text')
                    ->tooltip('View FMR')
                    ->visible(fn ($record) => $record->type === 'FMR'),

                // 🔹 Show FMD only if record type == 'FMD'
                ViewAction::make('FMD')
                    ->label('View FMD')
                    ->url(fn ($record) => route('print.formal-management-demand', [
                        'record' => $record->caReminderStep->disbursementVoucher,
                    ]))
                    ->button()
                    ->color('primary')
                    ->icon('heroicon-o-document-text')
                    ->tooltip('View FMD')
                    ->visible(fn ($record) => $record->type === 'FMD'),

                // 🔹 Show SCO only if record type == 'SCO'
                ViewAction::make('Endorsement FD')
                    ->label('View Endorsement')
                    ->url(fn ($record) => route('print.endorsement-for-fd', [
                        'record' => $record->caReminderStep->disbursementVoucher,
                    ]))
                    ->button()
                    ->color('primary')
                    ->icon('heroicon-o-exclamation')
                    ->tooltip('View Show Cause Order')
                    ->visible(fn ($record) => $record->type === 'ENDORSEMENT'),

                ViewAction::make('SCO')
                    ->label('View SCO')
                    ->url(fn ($record) => route('print.show-cause-order', [
                        'record' => $record->caReminderStep->disbursementVoucher,
                    ]))
                    ->button()
                    ->color('primary')
                    ->icon('heroicon-o-exclamation')
                    ->tooltip('View Show Cause Order')
                    ->visible(fn ($record) => $record->type === 'SCO'),

                // 🔹 Show FD only if record type == 'FD'
                // ViewAction::make('FD')
                //     ->label('View FD')
                //     ->url(fn($record) => route('print.endorsement-for-fd', [
                //         'record' => $record->caReminderStep->disbursementVoucher
                //     ]))
                //     ->button()
                //     ->color('success')
                //     ->icon('heroicon-o-document-text')
                //     ->tooltip('View Formal Demand Document')
                //     ->visible(fn($record) => $record->type === 'FD'),

                ViewAction::make('FD FILE')
                    ->label('FD FILE')
                    ->url(fn ($record) => route('print.endorsement-for-fd-file', [
                        'record' => $record->caReminderStep->disbursementVoucher,
                    ]))
                    ->button()
                    ->color('primary')
                    ->icon('heroicon-o-document-download')
                    ->tooltip('View Uploaded FD File')
                    ->visible(fn ($record) => $record->type === 'FD'),
                // ViewAction::make('View FD')
                //     ->label('View FD')
                //     ->url(fn ($record) => route('print.endorsement-for-fd', [
                //         'record' => $record->caReminderStep->disbursementVoucher,
                //     ]))
                //     ->button()
                //     ->color('primary')
                //     ->icon('heroicon-o-document-download')
                //     ->tooltip('View FD')
                //     ->visible(fn ($record) => $record->type === 'FD'),

            ]),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('type')
                ->options([
                    'FMR' => 'Formal Management Reminder',
                    'FMD' => 'Formal Management Demand',
                    'SCO' => 'Show Cause Order',
                    'FD' => 'Formal Demand',
                    'ENDORSEMENT' => 'Endorsement',
                ])
                ->label('Type'),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('caReminderStep.disbursementVoucher.dv_number')
                ->label('DV Number')
                ->searchable()
                ->sortable(),

            TextColumn::make('caReminderStep.disbursementVoucher.tracking_number')
                ->label('Tracking Number')
                ->searchable()
                ->sortable(),

            BadgeColumn::make('type')
                ->label('Type')
                ->colors([
                    'primary' => 'FMR',
                    'success' => 'FMD',
                    'warning' => 'SCO',
                    'danger' => 'FD',
                    'secondary' => 'ENDORSEMENT',
                ])
                ->formatStateUsing(fn ($state) => match ($state) {
                    'FMR' => 'Formal Management Reminder',
                    'FMD' => 'Formal Management Demand',
                    'SCO' => 'Show Cause Order',
                    'FD' => 'Formal Demand',
                    'ENDORSEMENT' => 'Endorsement',
                    default => 'Unknown',
                })
                ->sortable(),

            TextColumn::make('caReminderStep.disbursementVoucher.totalSum')
                ->label('Amount')
                ->money('PHP', true)
                ->sortable(),

            TextColumn::make('sent_at')
                ->label('Sent Date')
                ->dateTime('M d, Y h:i A')
                ->sortable(),

            TextColumn::make('sender_name')
                ->label('Sender')
                ->searchable()
                ->toggleable(),

            TextColumn::make('receiver_name')
                ->label('Receiver')
                ->searchable()
                ->toggleable(),

            TextColumn::make('step_data')
                ->label('Step Data (JSON)')
                ->wrap()
                ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT))
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime('M d, Y h:i A')
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
