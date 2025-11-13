<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use App\Http\Controllers\NotificationController;
use App\Jobs\SendSmsJob;
use App\Models\CaReminderStep;
use App\Models\EmployeeInformation;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

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

    protected function canViewFile($record, string $type): bool
    {
        $requiredSteps = [
            'FMR' => 2,
            'FMD' => 3,
            'SCO' => 4,
            'ENDORSEMENT' => 5,
            'FD' => 6,
        ];

        $requiredStep = $requiredSteps[$type] ?? 99;

        return $record->step > $requiredStep;
    }

    protected function getTableQuery(): Builder|Relation
    {
        $is_president = Auth::user()->employee_information->office_id == 51 && Auth::user()->employee_information->position_id == 34;
        $is_accountant = Auth::user()->employee_information->office_id == 3 && Auth::user()->employee_information->position_id == 15;
        if ($is_president) {
            return CaReminderStep::query()->latest()->whereIn('step', [4, 5])->whereHas('disbursement_voucher', function ($query) {
                $query->whereHas('liquidation_report', function ($query) {
                    $query->where('current_step_id', '<', 8000);
                })->orDoesntHave('liquidation_report');
            });
        } elseif ($is_accountant) {
            return CaReminderStep::query()->latest()->whereIn('step', [2, 3])->whereHas('disbursement_voucher', function ($query) {
                $query->whereHas('liquidation_report', function ($query) {
                    $query->where('current_step_id', '<', 8000);
                })->orDoesntHave('liquidation_report');
            });
        } else {
            return CaReminderStep::query()->latest()->whereIn('step', [6])->whereHas('disbursement_voucher', function ($query) {
                $query->whereHas('liquidation_report', function ($query) {
                    $query->where('current_step_id', '<', 8000);
                })->orDoesntHave('liquidation_report');
            });
        }
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('disbursementVoucher.dv_number')->label('DV Number')->searchable(),
            TextColumn::make('disbursementVoucher.tracking_number')->label('DV Tracking Number')->searchable(),
            TextColumn::make('disbursementVoucher.user.employee_information.full_name')->label('Requested By')->searchable(),
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
                        ->required(),
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
                        'Your cash advance with a DV number '.$record->disbursement_voucher->dv_number.' is due for liquidation. Please liquidate.',
                        $this->accounting,
                        $record->disbursementVoucher->user->name,
                        $this->accounting->id,
                        $record->disbursementVoucher->user,
                        route('print.formal-management-reminder', $record->disbursement_voucher),
                        $record->disbursement_voucher
                    );
                    $employee = $record->disbursementVoucher->user->employee_information ?? null;
                    $phone = $employee->contact_number ?? null;

                    if (! $phone) {
                        Log::warning("SMS not sent: No phone number for user ID {$record->disbursementVoucher->user->id} ");

                        return;
                    }

                    // $phone = '09366303145';

                    $sms = app(\App\Services\SmsService::class);
                    $formatted = $sms->formatPhoneNumber($phone);

                    // Validate number format
                    if (! $formatted || strlen($formatted) < 12) {
                        Log::warning("SMS not sent: Invalid number '{$phone}' for user ID {$record->disbursementVoucher->user->id}");

                        return;
                    }
$amount = number_format($record->disbursement_voucher->total_sum, 2);

$message = "FMR Reminder: Your Cash Advance (DV #{$record->disbursement_voucher->dv_number}) amounting to ₱{$amount} is now due for liquidation.";

                    // Dispatch SMS job
                    SendSmsJob::dispatch($formatted, $message);

                    Log::info("SMS queued for {$formatted} (user ID: {$record->disbursementVoucher->user->id})");

                })->requiresConfirmation()->visible(fn ($record) => $record->step == 2 && $record->is_sent == 0),
            Action::make('sendFMD')->label('Send FMD')->icon('ri-send-plane-fill')
                ->button()
                ->form([
                    TextInput::make('fmd_number')
                        ->label('FMD Number')
                        ->required(),
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
                        'Your cash advance with a DV number '.$record->disbursement_voucher->dv_number.' is due for liquidation. Please liquidate.',
                        $this->accounting,
                        $record->disbursementVoucher->user->name,
                        $this->accounting->id,
                        $record->disbursementVoucher->user,
                        route('print.formal-management-demand', $record->disbursement_voucher),
                        $record->disbursement_voucher
                    );
                })->requiresConfirmation()->visible(fn ($record) => $record->step == 3 && $record->is_sent == 0),
            Action::make('sendSOC')->label('Send SCO')->icon('ri-send-plane-fill')
                ->button()
                ->form([
                    TextInput::make('memorandum_number')
                        ->label('Memorandum Number')
                        ->required(),
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
                        'Your cash advance with a DV number '.$record->disbursement_voucher->dv_number.' is due for liquidation. Please liquidate.',
                        $this->accounting,
                        $record->disbursementVoucher->user->name,
                        $this->accounting->id,
                        $record->disbursementVoucher->user,
                        route('print.show-cause-order', $record->disbursement_voucher),
                        $record->disbursement_voucher
                    );
                })->requiresConfirmation()->visible(fn ($record) => $record->step == 4 && $record->is_sent == 0),
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
                        'type' => 'ENDORSEMENT',
                        // 'user_id' => Auth::id(),
                    ]);

                    $this->emit('historyCreated');

                    // Send FD
                    NotificationController::sendCASystemReminder(
                        'ENDORSEMENT',
                        'Endorsement For FD',
                        'Your cash advance with a DV number '.$record->disbursement_voucher->dv_number.' is due for liquidation. Please liquidate.',
                        $this->president,
                        $this->auditor->user->name,
                        $this->president->id,
                        $this->auditor->user,
                        route('print.endorsement-for-fd', $record->disbursement_voucher),
                        $record->disbursement_voucher
                    );
                })->requiresConfirmation()->visible(fn ($record) => $record->step == 5 && $record->is_sent == 0),
            Action::make('uploadFD')
                ->label('Upload FD')
                ->icon('ri-send-plane-fill')
                ->color('primary')
                ->button()
                ->form([
                    FileUpload::make('auditor_attachment')
                        ->label('Upload FD')
                        ->required()
                        ->preserveFilenames()
                        ->disk('public')
                        ->directory('fd')
                        ->acceptedFileTypes(['application/pdf'])
                        ->helperText('Only PDF files are allowed.'),
                    DatePicker::make('auditor_deadline')
                        ->label('Deadline')
                        ->required()
                        ->default(now())
                        ->minDate(now())
                        ->helperText('Select the deadline for the accountable person to respond.'),
                ])
                ->action(function ($record, $data) {
                    // ✅ Save FD attachment and deadline
                    $record->auditor_attachment = $data['auditor_attachment'];
                    $record->auditor_deadline = $data['auditor_deadline'];
                    $record->status = 'On-Going';
                    $record->step = 7;
                    $record->user_id = Auth::id();
                    $record->save();

                    // ✅ Create Reminder History
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
                        'receiver_name' => $record->disbursementVoucher->user->name, // ✅ correct receiver
                        'sent_at' => now(),
                        'type' => 'FD',
                    ]);

                    $fileUrl = Storage::disk('public')->url($record->auditor_attachment ?? '#');

                    NotificationController::sendCASystemReminder(
                        'FDS',
                        'Formal Demand File Sent',
                        'The Formal Demand file for your cash advance ('.
                            $record->disbursement_voucher->dv_number.
                            ') has been uploaded. Please review it immediately.',
                        $this->auditor->user->name,
                        $record->disbursementVoucher->user->name,
                        $this->auditor->id,
                        $record->disbursementVoucher->user,
                        $fileUrl, // 👈 send actual FD URL
                        $record->disbursement_voucher
                    );

                    $this->emit('historyCreated');

                    Notification::make()
                        ->title('Formal Demand Uploaded')
                        ->body('Notification sent to '.$record->disbursementVoucher->user->name)
                        ->success()
                        ->send();
                })
                ->visible(fn ($record) => $record->step == 6 && $record->is_sent == 1),

            ViewAction::make('FD')
                ->label('View FD File')
                ->url(fn ($record) => $record->caReminderStep?->disbursementVoucher?->id
                           ? route('print.endorsement-for-fd-file', [
                               'record' => $record->caReminderStep->disbursementVoucher->id,
                           ])
                           : '#'
                )
                ->button()
                ->color('primary')
                ->icon('heroicon-o-document-text')
                ->tooltip('View FD')
                ->visible(fn ($record) => $record->step === 6 &&
                    filled($record->auditor_attachment) &&
                    auth()->user()?->employee_information?->office_id === 61 &&
                    auth()->user()?->employee_information?->position_id === 31
                ),

            ActionGroup::make([
                ViewAction::make('view')
                    ->label('Preview DV')
                    ->openUrlInNewTab()
                    ->button()
                    ->color('success')
                    ->url(fn ($record) => route('disbursement-vouchers.show', ['disbursement_voucher' => $record->disbursement_voucher]), true),

                ViewAction::make('viewFMR')
                    ->label('View FMR')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => route('print.formal-management-reminder', $record->disbursement_voucher))
                    ->visible(fn ($record) => $this->canViewFile($record, 'FMR')),

                ViewAction::make('viewFMD')
                    ->label('View FMD')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => route('print.formal-management-demand', $record->disbursement_voucher))
                    ->visible(fn ($record) => $this->canViewFile($record, 'FMD')),

                ViewAction::make('viewSCO')
                    ->label('View SCO')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => route('print.show-cause-order', $record->disbursement_voucher))
                    ->visible(fn ($record) => $this->canViewFile($record, 'SCO')),

                ViewAction::make('viewENDORSEMENT')
                    ->label('View Endorsement')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => route('print.endorsement-for-fd', $record->disbursement_voucher))
                    ->visible(fn ($record) => $this->canViewFile($record, 'ENDORSEMENT')),

                ViewAction::make('viewFD')
                    ->label('View FD')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => Storage::disk('public')->url($record->auditor_attachment ?? '#'))
                    ->visible(fn ($record) => $this->canViewFile($record, 'FD')),

            ]),

        ];
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.cash-advance-reminders');
    }
}
