<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('cash-advance:check-reminders')->everyFiveMinutes()->runInBackground();
        // $schedule->command('cash-advance:check-reminders')->dailyAt('00:00');
        if (env('CA_REMINDER_FREQUENCY', 'daily') === 'daily') {
    $schedule->command('cash-advance:check-reminders')
             ->dailyAt('00:00')
             ->runInBackground();
} else {
    $schedule->command('cash-advance:check-reminders')
             ->everyFiveMinutes()
             ->runInBackground();
}

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
