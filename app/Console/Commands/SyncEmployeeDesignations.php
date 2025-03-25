<?php

namespace App\Console\Commands;

use App\Models\EmployeeInformation;
use Illuminate\Console\Command;

class SyncEmployeeDesignations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-designations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs employee designations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        EmployeeInformation::whereNotNull('campus_id')->whereNotNull('office_id')->whereNotNull('position_id')->each(function ($employee) {
            $employee->designations()->firstOrCreate([
                'campus_id' => $employee->campus_id,
                'office_id' => $employee->office_id,
                'position_id' => $employee->position_id,
            ]);
        });
        return Command::SUCCESS;
    }
}
