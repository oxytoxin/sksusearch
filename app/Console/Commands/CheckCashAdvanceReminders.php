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
        $cashAdvances = CaReminderStep::whereHas('disbursement_voucher.liquidation_report',function($query){
            $query->where('current_step_id','!=', 8000);
        })->where('status','On-Going')->get();

        foreach ($cashAdvances as $record) {

          
            // check if within the deadline
            $liquidationDeadline = Carbon::parse($record->liquidation_period_end_date);


            if ($now->greaterThanOrEqualTo($liquidationDeadline)) {


                switch ($record->step) {
                    case 1:


                        $record->update(['status' => 'Pending', 'is_sent' => false , 'step'=> 2]);

                    break;
                    case 2:
                        $record->update(['status' => 'Pending', 'is_sent' => false , 'step'=> 3]);
                        break;
                    case 3:
                        $record->update(['status' => 'Pending', 'is_sent' => false , 'step'=> 4]);
                        break;
                    case 4:
                        $record->update(['status' => 'Pending', 'is_sent' => false , 'step'=> 5]);
                        break;
                    case 5:
                        $record->update(['status' => 'Overdue', 'is_sent' => false , 'step'=> 1]);
                        Log::warning("Cash Advance #{$record->id} is overdue!");
                        break;
                }

                Log::info("Cash Advance #{$record->id} moved to {$record->status}");
            }
        }

        $this->info('Cash Advance Reminder Check completed.');

    }
}
