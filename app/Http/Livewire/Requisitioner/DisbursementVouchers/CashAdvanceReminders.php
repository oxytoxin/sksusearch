<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use Dom\Text;
use Livewire\Component;
use App\Models\CaReminderStep;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\NotificationController;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Relations\Relation;

class CashAdvanceReminders extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery(): Builder|Relation
    {
        return CaReminderStep::query();
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('disbursementVoucher.tracking_number')->label('DV Tracking Number'),
            TextColumn::make('disbursementVoucher.user.name')->label('Requested By'),
            TextColumn::make('status'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('sendFMR')->label('Send FMR')->icon('ri-send-plane-fill')
            ->button()
            ->action(function ($record) {


                // Send FMR

                 NotificationController::sendFMR(Auth::user(), $record->user, $record);


            })
            // ->url(fn($record) => route('requisitioner.liquidation-reports.create', [
            //     'disbursement_voucher' => $record
            // ]))
        ];
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.cash-advance-reminders');
    }
}
