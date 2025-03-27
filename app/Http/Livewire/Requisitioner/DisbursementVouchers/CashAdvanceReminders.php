<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use Dom\Text;
use Livewire\Component;
use App\Models\CaReminderStep;
use App\Models\EmployeeInformation;
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
    public $accounting;
    public $president;

    public function mount()
    {
        $this->accounting = EmployeeInformation::accountantUser();
        $this->president = EmployeeInformation::presidentUser();
    }

    protected function getTableQuery(): Builder|Relation
    {
        $is_president = auth()->user()->employee_information->office_id == 51 && auth()->user()->employee_information->position_id == 34;
        $is_accountant = auth()->user()->employee_information->office_id == 3 && auth()->user()->employee_information->position_id == 15;
        if($is_president)
        {
            return CaReminderStep::query()->whereIn('step', [4, 5])->whereHas('disbursement_voucher', function ($query) {
                $query->whereHas('liquidation_report', function ($query) {
                    $query->where('current_step_id', '>=', 8000);
                })->orDoesntHave('liquidation_report');
            });
        }else{
            return CaReminderStep::query()->whereIn('step', [2, 3])->whereHas('disbursement_voucher', function ($query) {
                $query->whereHas('liquidation_report', function ($query) {
                    $query->where('current_step_id', '>=', 8000);
                })->orDoesntHave('liquidation_report');
            });
        }

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


                $record->is_sent = 1;
                $record->status = 'On-Going';
                $record->save();
                // Send FMR
                NotificationController::sendCASystemReminder(
                    'FMR',
                    'Formal Management Reminder',
                    'Your cash advance with a tracking number '.$record->disbursement_voucher->tracking_number.' is due for liquidation. Please liquidate.',
                    $this->accounting,
                    $record->disbursementVoucher->user->name, $this->accounting->id, $record->disbursementVoucher->user,
                    route('print.formal-management-reminder'),
                    $record->disbursement_voucher);

            })->requiresConfirmation()->visible(fn ($record) => $record->step == 2 && $record->is_sent == 0),
            Action::make('sendFMD')->label('Send FMD')->icon('ri-send-plane-fill')
            ->button()
            ->action(function ($record) {

                $record->is_sent = 1;
                $record->status = 'On-Going';
                $record->save();
                // Send FMD
                NotificationController::sendCASystemReminder(
                    'FMD',
                    'Formal Management Demand',
                    'Your cash advance with a tracking number '.$record->disbursement_voucher->tracking_number.' is due for liquidation. Please liquidate.',
                    $this->accounting,
                    $record->disbursementVoucher->user->name, $this->accounting->id, $record->disbursementVoucher->user,
                    route('print.formal-management-demand'),
                    $record->disbursement_voucher);


            })->requiresConfirmation()->visible(fn ($record) => $record->step == 3 && $record->is_sent == 0),
            Action::make('sendSOC')->label('Send SOC')->icon('ri-send-plane-fill')
            ->button()
            ->action(function ($record) {

                $record->is_sent = 1;
                $record->status = 'On-Going';
                $record->save();
                // Send FMR
                NotificationController::sendCASystemReminder(
                    'SOC',
                    'Show Cause Order',
                    'Your cash advance with a tracking number '.$record->disbursement_voucher->tracking_number.' is due for liquidation. Please liquidate.',
                    $this->accounting,
                    $record->disbursementVoucher->user->name, $this->accounting->id, $record->disbursementVoucher->user,
                    route('print.show-cause-order'),
                    $record->disbursement_voucher);


            })->requiresConfirmation()->visible(fn ($record) => $record->step == 4 && $record->is_sent == 0),
            Action::make('sendFD')->label('Send FD')->icon('ri-send-plane-fill')
            ->button()
            ->action(function ($record) {

                $record->is_sent = 1;
                $record->status = 'On-Going';
                $record->save();
                // Send FMR
                NotificationController::sendCASystemReminder(
                    'FD',
                    'Endorsement For FD',
                    'Your cash advance with a tracking number '.$record->disbursement_voucher->tracking_number.' is due for liquidation. Please liquidate.',
                    $this->accounting,
                    $record->disbursementVoucher->user->name, $this->accounting->id, $record->disbursementVoucher->user,
                    route('print.endorsement-for-fd'),
                    $record->disbursement_voucher);


            })->requiresConfirmation()->visible(fn ($record) => $record->step == 5 && $record->is_sent == 0),
        ];
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.cash-advance-reminders');
    }
}
