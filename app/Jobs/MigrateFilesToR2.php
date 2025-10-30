<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;

class MigrateFilesToR2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $localDisk = Storage::disk('local');
        $r2Disk = Storage::disk('s3');
        $file = $this->file;

        if (!$r2Disk->exists($file)) {
            $r2Disk->put($file, $localDisk->get($file));
            logger("✅ Migrated: {$file}");
        } else {
            logger("⚠️ Skipped (already exists): {$file}");
        }
    }
}
