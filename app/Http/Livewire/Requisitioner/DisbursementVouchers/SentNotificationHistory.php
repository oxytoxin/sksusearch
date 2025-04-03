<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use Livewire\Component;
use App\Models\EmployeeInformation;
use Filament\Tables\Filters\Layout;
use App\Models\CaReminderStepHistory;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;

class SentNotificationHistory extends Component implements HasTable
{
    use InteractsWithTable;


    protected $listeners = ['historyCreated' => '$refresh'];
    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.sent-notification-history');
    }

    protected function getTableQuery()
    {
        $query = CaReminderStepHistory::query()->latest();

        if (auth()->id() === EmployeeInformation::presidentUser()->user_id) {
            $query->whereHas('caReminderStep', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        return $query;
    }

//     protected function getTableFiltersLayout(): ?string
// {
//     return Layout::AboveContentCollapsible;
// }

    protected function getTableActions(): array
    {
        return [

           ViewAction::make('view')
               ->label('Preview DV')
               ->modalContent(fn($record) => view('components.disbursement_vouchers.disbursement_voucher_view_no_layout', ['disbursement_voucher' => $record->caReminderStep->disbursement_voucher]))
               ->modalWidth('4xl')
               ->button()
               ->color('success')
               ->icon('heroicon-o-eye')
               ->tooltip('View Disbursement Voucher'),

           ViewAction::make('FMD')
               ->label('View FMD')
               ->modalContent(fn($record) => $record->type === 'FMD' ? view('reports.formal-management-demand', ['record' => $record->caReminderStep->disbursement_voucher]) : null)
               ->modalWidth('4xl')
               ->button()
               ->color('primary')
               ->icon('heroicon-o-document-text')
               ->tooltip('View FMD')
               ->visible(fn($record) => $record->type === 'FMD'),

           ViewAction::make('FMR')
               ->label('View FMR')
               ->modalContent(fn($record) => $record->type === 'FMR' ? view('reports.formal-management-reminder', ['record' => $record->caReminderStep->disbursement_voucher]) : null)
               ->modalWidth('4xl')
               ->button()
               ->color('primary')
               ->icon('heroicon-o-document-text')
               ->tooltip('View FMR')
               ->visible(fn($record) => $record->type === 'FMR'),

           ViewAction::make('FD')
               ->label('View FD')
               ->modalContent(fn($record) => $record->type === 'FD' ? view('reports.endorsement-for-f-d', ['record' => $record->caReminderStep->disbursement_voucher]) : null)
               ->modalWidth('4xl')
               ->button()
               ->color('primary')
               ->icon('heroicon-o-document-text')
               ->tooltip('View FD')
               ->visible(fn($record) => $record->type === 'FD'),

           ViewAction::make('SCO')
               ->label('View SCO')
               ->modalContent(fn($record) => $record->type === 'SCO' ? view('reports.show-cause-order', ['record' => $record->caReminderStep->disbursement_voucher]) : null)
               ->modalWidth('4xl')
               ->button()
               ->color('primary')
               ->icon('heroicon-o-document-text')
               ->tooltip('View SCO')
               ->visible(fn($record) => $record->type === 'SCO'),
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
                ])
                ->label('Type')
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('caReminderStep.disbursementVoucher.dv_number')->label('DV Number')->searchable(),
            TextColumn::make('caReminderStep.disbursementVoucher.tracking_number')->label('Tracking Number')->searchable(),
            TextColumn::make('type')->label('Type'),
            TextColumn::make('caReminderStep.disbursementVoucher.totalSum')->label('Amount'),
            TextColumn::make('sent_at')->label('Sent Date')->dateTime('F j, Y, g:i a'),
            TextColumn::make('sender_name')->label('Sender')->searchable(),
            TextColumn::make('receiver_name')->label('Receiver')->searchable(),
            // TextColumn::make('step_data')
            //     ->label('Step Data')
            //     ->wrap()
            //     ->formatStateUsing(fn($state) => json_encode($state, JSON_PRETTY_PRINT))
            //     ->toggleable()
            //     ,
            // TextColumn::make('created_at')->label('Created At'),
        ];
    }
}
