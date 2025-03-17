<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\CaReminderStep;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        // $now = Carbon::now();


        // $cashAdvances = CaReminderStep::where('status', 'Ongoing')->get();

        // foreach ($cashAdvances as $record) {
        //     $liquidationDeadline = Carbon::parse($record->liquidation_period_end_date);


        //     // check if it is liquidated before proceeding

        //     if ($now->greaterThanOrEqualTo($liquidationDeadline) && !$record->is_liquidated) {


        //         switch ($record->step) {
        //             case 1:
        //                 $record->update(['status' => 'Pending Step 2', 'is_sent' => false]);
        //                 break;
        //             case 2:
        //                 $record->update(['status' => 'Pending Step 3', 'is_sent' => false]);
        //                 break;
        //             case 3:
        //                 $record->update(['status' => 'Pending Step 4', 'is_sent' => false]);
        //                 break;
        //             case 4:
        //                 $record->update(['status' => 'Pending Step 5', 'is_sent' => false]);
        //                 break;
        //             case 5:
        //                 $record->update(['status' => 'Overdue', 'is_sent' => false]);
        //                 Log::warning("Cash Advance #{$record->id} is overdue!");
        //                 break;
        //         }

        //         Log::info("Cash Advance #{$record->id} moved to {$record->status}");
        //     }
        // }

        $this->info('Cash Advance Reminder Check completed.');

    }
}
