<?php

namespace App\Console\Commands;

use App\Jobs\MigrateFilesToR2;
use Illuminate\Console\Command;
use Storage;

use Exception;
class MigrateFilesToR2Command extends Command
{
    protected $signature = 'migrate-to-r2';

    /**
     * The console command description.
     */
    protected $description = 'Dispatch a job for each local file to migrate it to R2 storage';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $localDisk = Storage::disk('local');
            $allFiles = $localDisk->allFiles();

            foreach ($allFiles as $file) {
                MigrateFilesToR2::dispatch($file);
            }

            $this->info('✅ File migration jobs dispatched successfully (' . count($allFiles) . ' files).');
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error('❌ Error dispatching jobs: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
