<?php

namespace App\Console\Commands;

use App\Jobs\WfpStatusUpdate as JobsWfpStatusUpdate;
use App\Models\Wfp;
use Illuminate\Console\Command;

class WfpStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wfp:status-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $wfps = Wfp::get();
        foreach ($wfps as $wfp) {
            JobsWfpStatusUpdate::dispatch($wfp);
        }
        return Command::SUCCESS;
    }
}
