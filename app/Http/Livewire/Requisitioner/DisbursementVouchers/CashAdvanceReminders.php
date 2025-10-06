<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use Dom\Text;
use Livewire\Component;
use App\Models\CaReminderStep;
use App\Models\EmployeeInformation;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\NotificationController;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class CashAdvanceReminders extends Component implements HasTable
{
    use InteractsWithTable;
    public $accounting;
    public $president;
    public $auditor;

    public function mount()
    {
        $this->accounting = EmployeeInformation::accountantUser();
        $this->president = EmployeeInformation::presidentUser();
        $this->auditor = EmployeeInformation::auditorUser();
    }

    protected function getTableQuery(): Builder|Relation
    {
        $is_president = Auth::user()->employee_information->office_id == 51 && Auth::user()->employee_information->position_id == 34;
        $is_accountant = Auth::user()->employee_information->office_id == 3 && Auth::user()->employee_information->position_id == 15;
        if ($is_president) {
            return CaReminderStep::query()->whereIn('step', [4, 5])->whereHas('disbursement_voucher', function ($query) {
                $query->whereHas('liquidation_report', function ($query) {
                    $query->where('current_step_id', '<', 8000);
                })->orDoesntHave('liquidation_report');
            });
        } elseif ($is_accountant) {
            return CaReminderStep::query()->whereIn('step', [2, 3])->whereHas('disbursement_voucher', function ($query) {
                $query->whereHas('liquidation_report', function ($query) {
                    $query->where('current_step_id', '<', 8000);
                })->orDoesntHave('liquidation_report');
            });
        } else {
            return CaReminderStep::query()->whereIn('step', [6])->whereHas('disbursement_voucher', function ($query) {
                $query->whereHas('liquidation_report', function ($query) {
                    $query->where('current_step_id', '<', 8000);
                })->orDoesntHave('liquidation_report');
            });
        }
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('disbursementVoucher.dv_number')->label('DV Number'),
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
                ->form([
                    TextInput::make('fmr_number')
                        ->label('FMR Number')
                        ->required()
                ])
                ->action(function ($record, $data) {
                    // Update record
                    $record->is_sent = 1;
                    $record->status = 'On-Going';
                    $record->fmr_date = now();
                    $record->fmr_number = $data['fmr_number'];
                    $record->user_id = Auth::id();
                    $record->save();

                    // Store history
                    $record->caReminderStepHistories()->create([
                        'step_data' => [
                            'disbursement_voucher_id' => $record->disbursement_voucher_id,
                            'status' => $record->status,
                            'voucher_end_date' => $record->voucher_end_date,
                            'liquidation_period_end_date' => $record->liquidation_period_end_date,
                            'step' => $record->step,
                            'is_sent' => $record->is_sent,
                            'title' => $record->title,
                            'message' => $record->message,
                            'sent_at' => now(),
                        ],
                        'sender_name' => $this->accounting->user->name,
                        'sent_at' => now(),
                        'receiver_name' => $record->disbursementVoucher->user->name,
                        'type' => 'FMR',
                    ]);

                    $this->emit('historyCreated');

                    // Send FMR
                    NotificationController::sendCASystemReminder(
                        'FMR',
                        'Formal Management Reminder',
                        'Your cash advance with a tracking number ' . $record->disbursement_voucher->tracking_number . ' is due for liquidation. Please liquidate.',
                        $this->accounting,
                        $record->disbursementVoucher->user->name,
                        $this->accounting->id,
                        $record->disbursementVoucher->user,
                        route('print.formal-management-reminder', $record->disbursement_voucher),
                        $record->disbursement_voucher
                    );
                })->requiresConfirmation()->visible(fn($record) => $record->step == 2 && $record->is_sent == 0),
            Action::make('sendFMD')->label('Send FMD')->icon('ri-send-plane-fill')
                ->button()
                ->form([
                    TextInput::make('fmd_number')
                        ->label('FMD Number')
                        ->required()
                ])
                ->action(function ($record, $data) {
                    // Update record
                    $record->is_sent = 1;
                    $record->status = 'On-Going';
                    $record->fmd_date = now();
                    $record->fmd_number = $data['fmd_number'];
                    $record->user_id = Auth::id();
                    $record->save();

                    // Store history
                    $historyData = $record->caReminderStepHistories()->create([
                        'step_data' => [
                            'disbursement_voucher_id' => $record->disbursement_voucher_id,
                            'status' => $record->status,
                            'voucher_end_date' => $record->voucher_end_date,
                            'liquidation_period_end_date' => $record->liquidation_period_end_date,
                            'step' => $record->step,
                            'is_sent' => $record->is_sent,
                            'title' => $record->title,
                            'message' => $record->message,
                            'sent_at' => now(),
                        ],
                        'sender_name' => $this->accounting->user->name,
                        'sent_at' => now(),
                        'receiver_name' => $record->disbursementVoucher->user->name,
                        'type' => 'FMD',
                        // 'user_id' => Auth::id(),
                    ]);

                    $this->emit('historyCreated');

                    // Send FMD
                    NotificationController::sendCASystemReminder(
                        'FMD',
                        'Formal Management Demand',
                        'Your cash advance with a tracking number ' . $record->disbursement_voucher->tracking_number . ' is due for liquidation. Please liquidate.',
                        $this->accounting,
                        $record->disbursementVoucher->user->name,
                        $this->accounting->id,
                        $record->disbursementVoucher->user,
                        route('print.formal-management-demand', $record->disbursement_voucher),
                        $record->disbursement_voucher
                    );
                })->requiresConfirmation()->visible(fn($record) => $record->step == 3 && $record->is_sent == 0),
            Action::make('sendSOC')->label('Send SCO')->icon('ri-send-plane-fill')
                ->button()
                ->form([
                    TextInput::make('memorandum_number')
                        ->label('Memorandum Number')
                        ->required()
                ])
                ->action(function ($record, $data) {
                    // Update record
                    $record->is_sent = 1;
                    $record->status = 'On-Going';
                    $record->sco_date = now();
                    $record->memorandum_number = $data['memorandum_number'];
                    $record->user_id = Auth::id();
                    $record->save();

                    // Store history
                    $record->caReminderStepHistories()->create([
                        'step_data' => [
                            'disbursement_voucher_id' => $record->disbursement_voucher_id,
                            'status' => $record->status,
                            'voucher_end_date' => $record->voucher_end_date,
                            'liquidation_period_end_date' => $record->liquidation_period_end_date,
                            'step' => $record->step,
                            'is_sent' => $record->is_sent,
                            'title' => $record->title,
                            'message' => $record->message,
                            'sent_at' => now(),
                        ],
                        'sender_name' => $this->president->user->name,
                        'sent_at' => now(),
                        'receiver_name' => $record->disbursementVoucher->user->name,
                        'type' => 'SCO',
                        // 'user_id' => Auth::id(),
                    ]);

                    $this->emit('historyCreated');

                    // Send SCO
                    NotificationController::sendCASystemReminder(
                        'SCO',
                        'Show Cause Order',
                        'Your cash advance with a tracking number ' . $record->disbursement_voucher->tracking_number . ' is due for liquidation. Please liquidate.',
                        $this->accounting,
                        $record->disbursementVoucher->user->name,
                        $this->accounting->id,
                        $record->disbursementVoucher->user,
                        route('print.show-cause-order', $record->disbursement_voucher),
                        $record->disbursement_voucher
                    );
                })->requiresConfirmation()->visible(fn($record) => $record->step == 4 && $record->is_sent == 0),
            Action::make('sendFD')->label('Endorse FD')->icon('ri-send-plane-fill')
                ->button()
                ->action(function ($record) {
                    // Update record
                    $record->is_sent = 1;
                    $record->status = 'On-Going';
                    $record->fd_date = now();
                    $record->user_id = Auth::id();
                    $record->step = 6;
                    $record->save();

                    // Store history
                    $record->caReminderStepHistories()->create([
                        'step_data' => [
                            'disbursement_voucher_id' => $record->disbursement_voucher_id,
                            'status' => $record->status,
                            'voucher_end_date' => $record->voucher_end_date,
                            'liquidation_period_end_date' => $record->liquidation_period_end_date,
                            'step' => $record->step,
                            'is_sent' => $record->is_sent,
                            'title' => $record->title,
                            'message' => $record->message,
                            'sent_at' => now(),
                        ],
                        'sender_name' => $this->president->user->name,
                        'sent_at' => now(),
                        'receiver_name' => $this->auditor->user->name,
                        'type' => 'FD',
                        // 'user_id' => Auth::id(),
                    ]);

                    $this->emit('historyCreated');

                    // Send FD
                    NotificationController::sendCASystemReminder(
                        'FD',
                        'Endorsement For FD',
                        'Your cash advance with a tracking number ' . $record->disbursement_voucher->tracking_number . ' is due for liquidation. Please liquidate.',
                        $this->president,
                        $this->auditor->user->name,
                        $this->president->id,
                        $this->auditor->user,
                        route('print.endorsement-for-fd', $record->disbursement_voucher),
                        $record->disbursement_voucher
                    );
                })->requiresConfirmation()->visible(fn($record) => $record->step == 5 && $record->is_sent == 0),
            Action::make('uploadFD')->label('Upload FD')->icon('ri-send-plane-fill')
                ->button()
                ->form([
                //    FileUpload::make('auditor_attachment')
                //         ->label('Upload FD')
                //         ->required()
                //         ->preserveFilenames()
                //         ->disk('public')
                //         ->directory('fd')
                //         ->acceptedFileTypes(['application/pdf']),
                    DatePicker::make('auditor_deadline')
                        ->label('Deadline')
                        ->required()
                        ->default(now())
                        ->minDate(now()),
                ])
                ->action(function ($record, $data) {
                    // $record->auditor_attachment = $data['auditor_attachment'];
                    $record->auditor_deadline = $data['auditor_deadline'];
                    $record->status = 'On-Going';
                    $record->step = 7;
                    $record->user_id = Auth::id();
                    $record->save();
                    // Store history
                    $record->caReminderStepHistories()->create([
                        'step_data' => [
                            'disbursement_voucher_id' => $record->disbursement_voucher_id,
                            'status' => $record->status,
                            'voucher_end_date' => $record->voucher_end_date,
                            'liquidation_period_end_date' => $record->liquidation_period_end_date,
                            'step' => $record->step,
                            'is_sent' => $record->is_sent,
                            'title' => $record->title,
                            'message' => $record->message,
                            'sent_at' => now(),
                        ],
                        'sender_name' => $this->auditor->user->name,
                        'sent_at' => now(),
                        'receiver_name' => $this->auditor->user->name,
                        'type' => 'FD',
                        // 'user_id' => Auth::id(),
                    ]);
                    $this->emit('historyCreated');
                        $url = Storage::disk(app()->environment('local') ? 'local' : 'public')
            ->url($record->auditor_attachment ??'#');
                    NotificationController::sendCASystemReminder(
            'FDS',
            'Formal Demand File Sent',
            'The Formal Demand file for your cash advance (' .
                $record->disbursement_voucher->tracking_number .
                ') has been uploaded. Please review it immediately.',
            $this->auditor->user->name,
            $record->disbursementVoucher->user->name,
            $this->auditor->id,
            $record->disbursementVoucher->user,
            '#',
            $record->disbursement_voucher
        );

        // ✅ Show success notification (Filament)
        Notification::make()
            ->title('Formal Demand Uploaded')
            ->body('Notification sent to ' . $record->disbursementVoucher->user->name)
            ->success()
            ->send();


                })
                ->visible(fn($record) => $record->step == 6 && $record->is_sent == 1),
            ViewAction::make('view')
                ->label('Preview DV')
                ->openUrlInNewTab()
                ->button()
                ->color('success')
                ->url(fn($record) => route('disbursement-vouchers.show', ['disbursement_voucher' => $record->disbursement_voucher]), true),
        ];
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.cash-advance-reminders');
    }
}
