<?php

namespace App\Console\Commands;

use App\Jobs\MigrateFilesToR2;
use Illuminate\Console\Command;
use Storage;

use Exception;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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
            $rootPath = storage_path('app');
            $files = [];

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath, FilesystemIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS)
            );

            foreach ($iterator as $fileInfo) {
                if ($fileInfo->isFile()) {
                    // Relative path for dispatching
                    $relativePath = ltrim(str_replace($rootPath, '', $fileInfo->getPathname()), '/');
                    $files[] = $relativePath;

                    MigrateFilesToR2::dispatch($relativePath);
                }
            }

            $this->info("✅ File migration jobs dispatched: " . count($files));
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
