<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use Livewire\Component;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use App\Models\CaReminderStepHistory;

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

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('step_data')->label('Step Data'),
            TextColumn::make('created_at')->label('Created At'),
        ];
    }
}
