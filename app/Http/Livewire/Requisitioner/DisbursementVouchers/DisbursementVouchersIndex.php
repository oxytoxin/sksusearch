<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use App\Http\Controllers\NotificationController;
use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use App\Models\DisbursementVoucher;
use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Services\DisbursementVouchers\DisbursementVoucherWorkflowService;

class DisbursementVouchersIndex extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    protected function getTableQuery()
    {
        return DisbursementVoucher::whereForCancellation(false)->whereUserId(auth()->id())->latest('submitted_at');
    }

    protected function getTableColumns()
    {
        return [
            ...$this->officeTableColumns(),

        ];
    }

    public function getTableActions()
    {
        return [
            Action::make('Receive')->button()->action(function (DisbursementVoucher $record) {
                app(DisbursementVoucherWorkflowService::class)->receive($record, auth()->user(), [
                    'include_recipient' => false,
                ]);
                Notification::make()->title('Document Received')->success()->send();
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 1000;
                })
                ->requiresConfirmation(),
            Action::make('Forward')->button()->action(function ($record, $data) {
                app(DisbursementVoucherWorkflowService::class)->forward($record, auth()->user(), $data['remarks'] ?? null);
                Notification::make()->title('Document Forwarded')->success()->send();
            })
                ->form(function () {
                    return [
                        RichEditor::make('remarks')
                            ->label('Remarks (Optional)')
                            ->fileAttachmentsDisk('remarks'),
                    ];
                })
                ->modalWidth('4xl')
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 2000;
                })
                ->requiresConfirmation(),
            Action::make('Cancel')->action(function ($record) {
                DB::beginTransaction();
                $record->update([
                    'for_cancellation' => true,
                ]);
                $record->refresh();
                $record->activity_logs()->create([
                    'description' => 'Cancellation requested by ' . auth()->user()->employee_information->full_name,
                ]);
                if ($record->current_step_id < 5000 && $record->previous_step_id < 5000) {
                    $record->update([
                        'cancelled_at' => now(),
                    ]);
                    $record->activity_logs()->create([
                        'description' => 'Cancellation approved.',
                    ]);
                    DB::commit();
                    Notification::make()->title('Disbursement voucher cancelled.')->success()->send();
                    return;
                }
                DB::commit();

                // ========== REALTIME NOTIFICATION ==========
                // Cancellation needs approval - notify the signatory who must act on it.
                try {
                    $signatory = $record->signatory;
                    if ($signatory) {
                        $requesterName = auth()->user()->employee_information->full_name ?? 'A requisitioner';
                        NotificationController::sendGeneralNotification(
                            'disbursement_voucher_cancellation_requested',
                            'DV Cancellation Requested',
                            "{$requesterName} has requested cancellation of DV with ref. no. {$record->tracking_number}. Please review.",
                            $signatory,
                            route('disbursement-vouchers.show', $record->id)
                        );
                    }
                } catch (\Exception $e) {
                    \Log::error('Realtime notification failed: ' . $e->getMessage());
                }
                // ========== REALTIME NOTIFICATION END ==========

                Notification::make()->title('Disbursement voucher requested for cancellation.')->success()->send();
            })
                ->visible(fn ($record) => !$record->cheque_number && !$record->cancelled_at && !$record->for_cancellation)
                ->requiresConfirmation()
                ->button()
                ->color('danger'),
            ...$this->viewActions(),
        ];
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.disbursement-vouchers-index');
    }
}
