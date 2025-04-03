<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use Livewire\Component;
use App\Models\CaReminderStepHistory;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class SentNotificationHistory extends Component implements HasTable
{
    use InteractsWithTable;



    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.sent-notification-history');
    }

    protected function getTableQuery()
    {
        return CaReminderStepHistory::query();
    }

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
               ->tooltip('View Disbursement Voucher')
          
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('caReminderStep.disbursementVoucher.dv_number')->label('DV Number')->searchable(),
            TextColumn::make('sender_name')->label('Sender')->searchable(),
            TextColumn::make('receiver_name')->label('Receiver')->searchable(),
            TextColumn::make('caReminderStep.disbursementVoucher.totalSum')->label('Amount')->searchable(),
            TextColumn::make('sent_at')->label('Sent Date')->dateTime('F j, Y, g:i a'),
            TextColumn::make('type')->label('Type')->searchable(),
            // TextColumn::make('step_data')->label('Step Data'),
            // TextColumn::make('created_at')->label('Created At'),
        ];
    }
}
