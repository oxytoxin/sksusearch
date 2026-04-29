<?php

namespace App\Console\Commands;

use Notification;
use Carbon\Carbon;
use App\Models\CaReminderStep;
use Illuminate\Console\Command;
use App\Models\EmployeeInformation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationController;
use App\Models\User;

class CheckCashAdvanceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cash-advance:check-reminders';



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and send reminders for cash advance liquidation based on steps';

    /**
     * Execute the console command.
     *
     * Per-step waiting periods (and the days-vs-minutes demo toggle) live in
     * config/cash_advance.php and are env-overridable. See
     * smsdocumentation/CASH_ADVANCE_REMINDER_FLOW.md for the toggle workflow.
     *
     * @return int
     */
    public function handle()
    {
        $now        = Carbon::now();
        $demoMode   = (bool) config('cash_advance.demo_mode');
        $waitMethod = $demoMode ? 'addMinutes' : 'addDays';
        $waitUnit   = $demoMode ? 'minutes' : 'days';
        $waitValues = $demoMode
            ? config('cash_advance.wait_minutes_per_step_demo')
            : config('cash_advance.wait_days_per_step');

        if ($demoMode) {
            Log::info('Cash Advance Reminder cron running in DEMO mode', [
                'wait_minutes_per_step' => $waitValues,
            ]);
        }


        // get all disbursement that is not liquidated
        // $cashAdvances = CaReminderStep::with('disbursement_voucher.liquidation_report',function($query){
        $cashAdvances = CaReminderStep::whereHas('disbursement_voucher', function ($query) {
            $query->whereHas('liquidation_report', function ($query) {
                $query->where('current_step_id', '<', 8000);
            })->orDoesntHave('liquidation_report');
        })->where('status', 'On-Going')->get();

        foreach ($cashAdvances as $record) {

            $receiver  = EmployeeInformation::accountantUser();
            $president = EmployeeInformation::presidentUser();
            $dvNumber  = $record->disbursement_voucher->dv_number ?? 'N/A';

            // Pick the trigger date for the current step. Step 1 fires when the
            // original liquidation deadline passes; subsequent steps wait a
            // configurable period (days in production, minutes in demo) after
            // the previous notice was actually sent.
            $triggerDate = match ($record->step) {
                1 => $record->liquidation_period_end_date
                        ? Carbon::parse($record->liquidation_period_end_date)
                        : null,
                2 => $record->fmr_date
                        ? Carbon::parse($record->fmr_date)->{$waitMethod}($waitValues[2])
                        : null,
                3 => $record->fmd_date
                        ? Carbon::parse($record->fmd_date)->{$waitMethod}($waitValues[3])
                        : null,
                4 => $record->sco_date
                        ? Carbon::parse($record->sco_date)->{$waitMethod}($waitValues[4])
                        : null,
                default => null,
            };

            if ($triggerDate && $now->greaterThanOrEqualTo($triggerDate)) {
                switch ($record->step) {
                    case 1:
                        NotificationController::sendCASystemReminder(
                            'Cash Advance Reminder',
                            'Action required: Send FMR',
                            "Cash advance DV {$dvNumber} has reached its liquidation deadline. Please send the Formal Management Reminder (FMR).",
                            'System',
                            $receiver->user->name,
                            null,
                            $receiver->user,
                            route('requisitioner.ca-reminders'),
                            $record->disbursement_voucher
                        );
                        $record->update(['status' => 'Pending', 'is_sent' => false, 'step' => 2]);
                        break;
                    case 2:
                        NotificationController::sendCASystemReminder(
                            'Cash Advance Reminder',
                            'Action required: Send FMD',
                            "FMR for DV {$dvNumber} was sent {$waitValues[2]} {$waitUnit} ago without liquidation. Please send the Formal Management Demand (FMD).",
                            'System',
                            $receiver->user->name,
                            null,
                            $receiver->user,
                            route('requisitioner.ca-reminders'),
                            $record->disbursement_voucher
                        );
                        $record->update(['status' => 'Pending', 'is_sent' => false, 'step' => 3]);
                        break;
                    case 3:
                        NotificationController::sendCASystemReminder(
                            'Cash Advance Reminder',
                            'Action required: Send SCO',
                            "FMD for DV {$dvNumber} was sent {$waitValues[3]} {$waitUnit} ago without liquidation. Please send the Show Cause Order (SCO).",
                            'System',
                            $president->user->name,
                            null,
                            $president->user,
                            route('requisitioner.ca-reminders'),
                            $record->disbursement_voucher
                        );
                        $record->update(['status' => 'Pending', 'is_sent' => false, 'step' => 4]);
                        break;
                    case 4:
                        NotificationController::sendCASystemReminder(
                            'Cash Advance Reminder',
                            'Action required: Endorse for FD',
                            "SCO for DV {$dvNumber} was sent {$waitValues[4]} {$waitUnit} ago without liquidation. Please endorse the case to the Resident Auditor for Formal Demand.",
                            'System',
                            $president->user->name,
                            null,
                            $president->user,
                            route('requisitioner.ca-reminders'),
                            $record->disbursement_voucher
                        );
                        $record->update(['status' => 'Pending', 'is_sent' => false, 'step' => 5]);
                        break;
                }

                Log::info("Cash Advance #{$record->id} step bumped to {$record->step}", [
                    'dv_number'    => $dvNumber,
                    'trigger_date' => $triggerDate->toDateTimeString(),
                ]);
            }
        }

        $this->info('Cash Advance Reminder Check completed.');
    }
}
