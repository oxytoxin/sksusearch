<?php

namespace App\Console\Commands;

use App\Jobs\AttachWfpIdToFundDraftItems;
use App\Models\FundAllocation;
use App\Models\Wfp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AttachWfpId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attach:wfp-id';

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
        $data = FundAllocation::whereHas('fundDrafts')->with('fundDrafts.draft_items')->get();
        foreach ($data as $key => $value) {
            AttachWfpIdToFundDraftItems::dispatch($value);
        }
        return Command::SUCCESS;
    }
}
