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
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();





        // get all disbursement that is not liquidated
        // $cashAdvances = CaReminderStep::with('disbursement_voucher.liquidation_report',function($query){
        $cashAdvances = CaReminderStep::whereHas('disbursement_voucher', function ($query) {
            $query->whereHas('liquidation_report', function ($query) {
                $query->where('current_step_id', '<', 8000);
            })->orDoesntHave('liquidation_report');
        })->where('status', 'On-Going')->get();

        foreach ($cashAdvances as $record) {

            //$record->created_at plus 5mins
            $updated_at = Carbon::parse($record->updated_at);
            $updated_at_deadline = $updated_at->addMinutes(2);

            // check if within the deadline
            $liquidationDeadline = Carbon::parse($record->liquidation_period_end_date);
            $receiver = EmployeeInformation::accountantUser();
            $president = EmployeeInformation::presidentUser();
            $auditor = EmployeeInformation::auditorUser();

            //step 1
            //step 2
            //step 3
            //step 4
            //step 5
            //step 6


            // if ($now->greaterThanOrEqualTo($liquidationDeadline)) {
            if ($now->greaterThanOrEqualTo($updated_at_deadline)) {
                switch ($record->step) {
                    case 1:
                        NotificationController::sendCASystemReminder(
                            'Cash Advance Reminder',
                            'Formal Management Reminder',
                            'A cash advance with a tracking number ' . $record->disbursement_voucher->tracking_number . ' is due for liquidation. Please remind the user to submit a liquidation report.',
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
                            'Formal Management Demand',
                            'A cash advance with a tracking number ' . $record->disbursement_voucher->tracking_number . ' is due for liquidation. Please remind the user to submit a liquidation report.',
                            'System',
                            $receiver->user->name,
                            null,
                            $receiver->user,
                            route('requisitioner.ca-reminders'),
                            $record->disbursement_voucher
                        );
                        $record->update(['status' => 'Pending', 'is_sent' => false, 'step' => 3, 'is_sent' => 0]);
                        break;
                    case 3:
                        NotificationController::sendCASystemReminder(
                            'Cash Advance Reminder',
                            'Show Cause Order',
                            'A cash advance with a tracking number ' . $record->disbursement_voucher->tracking_number . ' is due for liquidation. Please remind the user to submit a liquidation report.',
                            'System',
                            $president->user->name,
                            null,
                            $president->user,
                            route('requisitioner.ca-reminders'),
                            $record->disbursement_voucher
                        );
                        $record->update(['status' => 'Pending', 'is_sent' => false, 'step' => 4, 'is_sent' => 0]);
                        break;
                    case 4:
                        NotificationController::sendCASystemReminder(
                            'Cash Advance Reminder',
                            'Endorsement for FD',
                            'A cash advance with a tracking number ' . $record->disbursement_voucher->tracking_number . ' is due for liquidation. Please remind the user to submit a liquidation report.',
                            'System',
                            $president->user->name,
                            null,
                            $president->user,
                            route('requisitioner.ca-reminders'),
                            $record->disbursement_voucher
                        );
                        $record->update(['status' => 'Pending', 'is_sent' => false, 'step' => 5, 'is_sent' => 0]);
                        break;
                        // case 5:
                        //     NotificationController::sendCASystemReminder(
                        //         'Cash Advance Reminder',
                        //         'Unliquidated',
                        //         'A cash advance with a tracking number '.$record->disbursement_voucher->tracking_number.' has beed marked Unliquidated.',
                        //         'System',
                        //         $president->user->name, null, $president->user,
                        //         route('requisitioner.ca-reminders'),
                        //         $record->disbursement_voucher);
                        //     $record->update(['status' => 'Unliquidated', 'is_sent' => false , 'step'=> 1, 'is_sent' => 0]);
                        //     Log::warning("Cash Advance #{$record->id} is overdue!");
                        //     break;
                }

                Log::info("Cash Advance #{$record->id} moved to {$record->status}");
            }
        }

        $this->info('Cash Advance Reminder Check completed.');
    }
}
