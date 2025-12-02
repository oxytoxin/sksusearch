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

                    // ========== SMS NOTIFICATION START ==========
                    try {
                        // Validate required relationships exist
                        if (!$record->disbursementVoucher || !$record->disbursementVoucher->user) {
                            Log::warning("SMS not sent: Missing disbursement voucher or user relationship", [
                                'ca_reminder_id' => $record->id
                            ]);
                        } else {
                            // Get employee phone number
                            $user = $record->disbursementVoucher->user;
                            $employee = $user->employee_information ?? null;

                            // For production: use actual phone number
                            // $phone = $employee->contact_number ?? null;
                            // For testing: uncomment below
                            $phone = "09273464891";

                            // Check if phone number exists
                            if (!$phone) {
                                Log::warning("SMS not sent: No phone number for user", [
                                    'user_id' => $user->id,
                                    'user_name' => $user->name
                                ]);
                            } else {
                                // Prepare SMS data with null safety
                                $dv = $record->disbursement_voucher;
                                $amount = $dv->total_sum ? number_format($dv->total_sum, 2) : '0.00';
                                $checkNumber = $dv->cheque_number ?? 'N/A';
                                $liquidationDeadline = $record->liquidation_period_end_date
                                    ? \Carbon\Carbon::parse($record->liquidation_period_end_date)->format('M d, Y')
                                    : 'N/A';

                                // Get purposes with empty check
                                $particulars = $dv->disbursement_voucher_particulars;
                                if ($particulars && $particulars->count() > 0) {
                                    $purposes = $particulars->pluck('purpose')->filter()->join(', ');
                                } else {
                                    $purposes = 'No purpose specified';
                                }

                                // Ensure purposes is not empty
                                if (empty(trim($purposes))) {
                                    $purposes = 'No purpose specified';
                                }

                                // Build SMS message
                                $message = "FMR No. {$data['fmr_number']} has been sent to you for your unliquidated cash advance disbursed via check/ADA number {$checkNumber} amounting to ₱{$amount} for the following purpose: \"{$purposes}\". Your liquidation deadline is on {$liquidationDeadline}.";

                                // Dispatch SMS job with context and user IDs
                                SendSmsJob::dispatch(
                                    $phone,
                                    $message,
                                    'FMR',  // context
                                    $user->id,  // recipient user_id
                                    Auth::id()  // sender_id
                                );

                                Log::info("SMS queued successfully", [
                                    'phone' => $phone,
                                    'user_id' => $user->id,
                                    'dv_number' => $dv->dv_number ?? 'N/A',
                                    'fmr_number' => $data['fmr_number']
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("SMS notification failed", [
                            'error' => $e->getMessage(),
                            'line' => $e->getLine(),
                            'file' => $e->getFile(),
                            'ca_reminder_id' => $record->id ?? null
                        ]);
                        // Don't throw - allow the main FMR action to complete successfully
                    }
                    // ========== SMS NOTIFICATION END ==========

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

                    // ========== SMS NOTIFICATION START ==========
                    try {
                        // Validate required relationships exist
                        if (!$record->disbursementVoucher || !$record->disbursementVoucher->user) {
                            Log::warning("SMS not sent: Missing disbursement voucher or user relationship", [
                                'ca_reminder_id' => $record->id,
                                'context' => 'FMD'
                            ]);
                        } else {
                            // Get employee phone number
                            $user = $record->disbursementVoucher->user;
                            $employee = $user->employee_information ?? null;

                            // For production: use actual phone number
                            // $phone = $employee->contact_number ?? null;
                            // For testing: uncomment below
                            $phone = "09273464891";

                            // Check if phone number exists
                            if (!$phone) {
                                Log::warning("SMS not sent: No phone number for user", [
                                    'user_id' => $user->id,
                                    'user_name' => $user->name,
                                    'context' => 'FMD'
                                ]);
                            } else {
                                // Prepare SMS data with null safety
                                $dv = $record->disbursement_voucher;
                                $amount = $dv->total_sum ? number_format($dv->total_sum, 2) : '0.00';
                                $checkNumber = $dv->cheque_number ?? 'N/A';

                                // Get liquidation deadline - for FMD it's "was on" (past tense)
                                $liquidationDeadline = $record->liquidation_period_end_date ? \Carbon\Carbon::parse($record->liquidation_period_end_date)->format('M d, Y') : 'N/A';

                                // Get FMR number (earlier reminder)
                                $fmrNumber = $record->fmr_number ?? 'N/A';

                                // Get purposes with empty check
                                $particulars = $dv->disbursement_voucher_particulars;
                                if ($particulars && $particulars->count() > 0) {
                                    $purposes = $particulars->pluck('purpose')->filter()->join(', ');
                                } else {
                                    $purposes = 'No purpose specified';
                                }

                                // Ensure purposes is not empty
                                if (empty(trim($purposes))) {
                                    $purposes = 'No purpose specified';
                                }

                                // Build SMS message for FMD
                                $message = "FMD No. {$data['fmd_number']} has been sent to you for your unliquidated cash advance disbursed via check/ADA number {$checkNumber} amounting to ₱{$amount} for the following purpose: \"{$purposes}\". Your liquidation deadline was on {$liquidationDeadline}. FMR No. {$fmrNumber} was earlier sent to you as a reminder.";

                                // Dispatch SMS job with context and user IDs
                                SendSmsJob::dispatch(
                                    $phone,
                                    $message,
                                    'FMD',  // context
                                    $user->id,  // recipient user_id
                                    Auth::id()  // sender_id
                                );

                                Log::info("SMS queued successfully", [
                                    'phone' => $phone,
                                    'user_id' => $user->id,
                                    'dv_number' => $dv->dv_number ?? 'N/A',
                                    'fmd_number' => $data['fmd_number'],
                                    'fmr_number' => $fmrNumber
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("SMS notification failed", [
                            'error' => $e->getMessage(),
                            'line' => $e->getLine(),
                            'file' => $e->getFile(),
                            'ca_reminder_id' => $record->id ?? null,
                            'context' => 'FMD'
                        ]);
                        // Don't throw - allow the main FMD action to complete successfully
                    }
                    // ========== SMS NOTIFICATION END ==========
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

                    // ========== SMS NOTIFICATION START ==========
                    try {
                        // Validate required relationships exist
                        if (!$record->disbursementVoucher || !$record->disbursementVoucher->user) {
                            Log::warning("SMS not sent: Missing disbursement voucher or user relationship", [
                                'ca_reminder_id' => $record->id,
                                'context' => 'SCO'
                            ]);
                        } else {
                            // Get employee phone number
                            $user = $record->disbursementVoucher->user;
                            $employee = $user->employee_information ?? null;

                            // For production: use actual phone number
                            // $phone = $employee->contact_number ?? null;
                            // For testing: uncomment below
                            $phone = "09273464891";

                            // Check if phone number exists
                            if (!$phone) {
                                Log::warning("SMS not sent: No phone number for user", [
                                    'user_id' => $user->id,
                                    'user_name' => $user->name,
                                    'context' => 'SCO'
                                ]);
                            } else {
                                // Prepare SMS data with null safety
                                $dv = $record->disbursement_voucher;
                                $amount = $dv->total_sum ? number_format($dv->total_sum, 2) : '0.00';
                                $checkNumber = $dv->cheque_number ?? 'N/A';

                                // Get liquidation deadline - for SCO it's "was on" (past tense)
                                $liquidationDeadline = $record->liquidation_period_end_date   ? \Carbon\Carbon::parse($record->liquidation_period_end_date)->format('M d, Y')  : 'N/A';

                                // Get FMR and FMD numbers (earlier notices)
                                $fmrNumber = $record->fmr_number ?? 'N/A';
                                $fmdNumber = $record->fmd_number ?? 'N/A';

                                // Get purposes with empty check
                                $particulars = $dv->disbursement_voucher_particulars;
                                if ($particulars && $particulars->count() > 0) {
                                    $purposes = $particulars->pluck('purpose')->filter()->join(', ');
                                } else {
                                    $purposes = 'No purpose specified';
                                }

                                // Ensure purposes is not empty
                                if (empty(trim($purposes))) {
                                    $purposes = 'No purpose specified';
                                }

                                // Build SMS message for SCO
                                $message = "Memorandum No. {$data['memorandum_number']} has been sent to you, ordering you to show cause for your failure to liquidate your cash advance disbursed via check/ADA number {$checkNumber} amounting to ₱{$amount} for the following purpose: \"{$purposes}\". Your liquidation deadline was on {$liquidationDeadline}. Earlier notices in the form of FMR No. {$fmrNumber} and FMD No. {$fmdNumber} were already sent to you.";

                                // Dispatch SMS job with context and user IDs
                                SendSmsJob::dispatch(
                                    $phone,
                                    $message,
                                    'SCO',  // context
                                    $user->id,  // recipient user_id
                                    Auth::id()  // sender_id
                                );

                                Log::info("SMS queued successfully", [
                                    'phone' => $phone,
                                    'user_id' => $user->id,
                                    'dv_number' => $dv->dv_number ?? 'N/A',
                                    'memorandum_number' => $data['memorandum_number'],
                                    'fmr_number' => $fmrNumber,
                                    'fmd_number' => $fmdNumber
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("SMS notification failed", [
                            'error' => $e->getMessage(),
                            'line' => $e->getLine(),
                            'file' => $e->getFile(),
                            'ca_reminder_id' => $record->id ?? null,
                            'context' => 'SCO'
                        ]);
                        // Don't throw - allow the main SCO action to complete successfully
                    }
                    // ========== SMS NOTIFICATION END ==========
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

                    // ========== SMS NOTIFICATION START (ENDORSEMENT - TWO RECIPIENTS) ==========
                    try {
                        // Validate required relationships exist
                        if (!$record->disbursementVoucher || !$record->disbursementVoucher->user) {
                            Log::warning("SMS not sent: Missing disbursement voucher or user relationship", [
                                'ca_reminder_id' => $record->id,
                                'context' => 'ENDORSEMENT'
                            ]);
                        } else {
                            // Prepare common SMS data with null safety
                            $dv = $record->disbursement_voucher;
                            $payee = $dv->user;
                            $payeeName = $payee->name ?? 'Unknown';
                            $amount = $dv->total_sum ? number_format($dv->total_sum, 2) : '0.00';
                            $checkNumber = $dv->cheque_number ?? 'N/A';

                            // Get FMR, FMD, and Memorandum numbers
                            $fmrNumber = $record->fmr_number ?? 'N/A';
                            $fmdNumber = $record->fmd_number ?? 'N/A';
                            $memorandumNumber = $record->memorandum_number ?? 'N/A';

                            // ===== SMS 1: TO PAYEE (Person with unliquidated cash advance) =====
                            $payeeEmployee = $payee->employee_information ?? null;
                            // $payeePhone = $payeeEmployee->contact_number ?? null;

                            // For production: use actual phone number
                            // For testing: uncomment below
                            $payeePhone = "09273464891";

                            if (!$payeePhone) {
                                Log::warning("SMS not sent to payee: No phone number", [
                                    'user_id' => $payee->id,
                                    'user_name' => $payeeName,
                                    'context' => 'ENDORSEMENT_PAYEE'
                                ]);
                            } else {
                                // Build SMS message for PAYEE
                                $payeeMessage = "Your unliquidated cash advance disbursed via check/ADA number {$checkNumber} amounting to ₱{$amount} has been endorsed to the Resident Auditor. The cash advance remains unliquidated despite service of notices in the form of FMR No. {$fmrNumber}, FMD No. {$fmdNumber}, and Memorandum No. {$memorandumNumber}.";

                                // Dispatch SMS to payee
                                SendSmsJob::dispatch(
                                    $payeePhone,
                                    $payeeMessage,
                                    'ENDORSEMENT_PAYEE',  // context
                                    $payee->id,  // recipient user_id
                                    Auth::id()  // sender_id
                                );

                                Log::info("SMS queued successfully to payee", [
                                    'phone' => $payeePhone,
                                    'user_id' => $payee->id,
                                    'user_name' => $payeeName,
                                    'dv_number' => $dv->dv_number ?? 'N/A',
                                    'context' => 'ENDORSEMENT_PAYEE'
                                ]);
                            }

                            // ===== SMS 2: TO AUDITOR (Resident Auditor) =====
                            if (!$this->auditor || !$this->auditor->user) {
                                Log::warning("SMS not sent to auditor: Missing auditor relationship", [
                                    'ca_reminder_id' => $record->id,
                                    'context' => 'ENDORSEMENT_AUDITOR'
                                ]);
                            } else {
                                $auditorUser = $this->auditor->user;
                                $auditorEmployee = $auditorUser->employee_information ?? null;
                                // $auditorPhone = $auditorEmployee->contact_number ?? null;

                                // For production: use actual phone number
                                // For testing: uncomment below
                                $auditorPhone = "09273464891";

                                if (!$auditorPhone) {
                                    Log::warning("SMS not sent to auditor: No phone number", [
                                        'user_id' => $auditorUser->id,
                                        'user_name' => $auditorUser->name,
                                        'context' => 'ENDORSEMENT_AUDITOR'
                                    ]);
                                } else {
                                    // Build SMS message for AUDITOR
                                    $auditorMessage = "The unliquidated cash advance of {$payeeName} under check/ADA number {$checkNumber} amounting to ₱{$amount} has been endorsed to you by the Office of the President. The payee had previously been issued notices in the form of FMR No. {$fmrNumber}, FMD No. {$fmdNumber}, and Memorandum No. {$memorandumNumber}.";

                                    // Dispatch SMS to auditor
                                    SendSmsJob::dispatch(
                                        $auditorPhone,
                                        $auditorMessage,
                                        'ENDORSEMENT_AUDITOR',  // context
                                        $auditorUser->id,  // recipient user_id
                                        Auth::id()  // sender_id
                                    );

                                    Log::info("SMS queued successfully to auditor", [
                                        'phone' => $auditorPhone,
                                        'user_id' => $auditorUser->id,
                                        'user_name' => $auditorUser->name,
                                        'payee_name' => $payeeName,
                                        'dv_number' => $dv->dv_number ?? 'N/A',
                                        'context' => 'ENDORSEMENT_AUDITOR'
                                    ]);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("SMS notification failed for endorsement", [
                            'error' => $e->getMessage(),
                            'line' => $e->getLine(),
                            'file' => $e->getFile(),
                            'ca_reminder_id' => $record->id ?? null,
                            'context' => 'ENDORSEMENT'
                        ]);
                        // Don't throw - allow the main endorsement action to complete successfully
                    }
                    // ========== SMS NOTIFICATION END ==========
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

                    // ========== SMS NOTIFICATION START (FORMAL DEMAND) ==========
                    try {
                        // Validate required relationships exist
                        if (!$record->disbursementVoucher || !$record->disbursementVoucher->user) {
                            Log::warning("SMS not sent: Missing disbursement voucher or user relationship", [
                                'ca_reminder_id' => $record->id,
                                'context' => 'FD'
                            ]);
                        } else {
                            // Get payee (recipient of FD)
                            $payee = $record->disbursementVoucher->user;
                            $payeeEmployee = $payee->employee_information ?? null;
                            // $payeePhone = $payeeEmployee->contact_number ?? null;

                            // For production: use actual phone number
                            // For testing: uncomment below
                            $payeePhone = "09273464891";

                            // Check if phone number exists
                            if (!$payeePhone) {
                                Log::warning("SMS not sent: No phone number for payee", [
                                    'user_id' => $payee->id,
                                    'user_name' => $payee->name,
                                    'context' => 'FD'
                                ]);
                            } else {
                                // Prepare SMS data with null safety
                                $dv = $record->disbursement_voucher;
                                $amount = $dv->total_sum ? number_format($dv->total_sum, 2) : '0.00';
                                $checkNumber = $dv->cheque_number ?? 'N/A';

                                // Get FD reference number - use file name or generate from DV number
                                $fileName = $record->auditor_attachment ? basename($record->auditor_attachment, '.pdf') : null;
                                $fdRefNumber = $fileName ?? "FD-{$dv->dv_number}";

                                // Get FMR, FMD, and Memorandum numbers
                                $fmrNumber = $record->fmr_number ?? 'N/A';
                                $fmdNumber = $record->fmd_number ?? 'N/A';
                                $memorandumNumber = $record->memorandum_number ?? 'N/A';

                                // Get purposes with empty check
                                $particulars = $dv->disbursement_voucher_particulars;
                                if ($particulars && $particulars->count() > 0) {
                                    $purposes = $particulars->pluck('purpose')->filter()->join(', ');
                                } else {
                                    $purposes = 'No purpose specified';
                                }

                                // Ensure purposes is not empty
                                if (empty(trim($purposes))) {
                                    $purposes = 'No purpose specified';
                                }

                                // Build SMS message for FD
                                $message = "The Commission on Audit has electronically served your Formal Demand with ref. no. {$fdRefNumber} for your failure to liquidate your cash advance disbursed via check/ADA number {$checkNumber} amounting to ₱{$amount} for the following purpose: \"{$purposes}\". The cash advance remains unliquidated despite service of notices in the form of FMR No. {$fmrNumber}, FMD No. {$fmdNumber}, and Memorandum No. {$memorandumNumber}.";

                                // Dispatch SMS job with context and user IDs
                                SendSmsJob::dispatch(
                                    $payeePhone,
                                    $message,
                                    'FD',  // context
                                    $payee->id,  // recipient user_id
                                    Auth::id()  // sender_id (auditor)
                                );

                                Log::info("SMS queued successfully for FD", [
                                    'phone' => $payeePhone,
                                    'user_id' => $payee->id,
                                    'user_name' => $payee->name,
                                    'dv_number' => $dv->dv_number ?? 'N/A',
                                    'fd_ref_number' => $fdRefNumber,
                                    'context' => 'FD'
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("SMS notification failed for FD", [
                            'error' => $e->getMessage(),
                            'line' => $e->getLine(),
                            'file' => $e->getFile(),
                            'ca_reminder_id' => $record->id ?? null,
                            'context' => 'FD'
                        ]);
                        // Don't throw - allow the main FD upload action to complete successfully
                    }
                    // ========== SMS NOTIFICATION END ==========

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
