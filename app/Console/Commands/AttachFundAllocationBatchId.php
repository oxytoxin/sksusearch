<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FundAllocationBatches;
use App\Jobs\WfpStatusUpdate;
use App\Models\FundAllocation;
use App\Models\Wfp;

class AttachFundAllocationBatchId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:batch';

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
        $data =  FundAllocation::query()
            ->fromSub(function ($query) {
                $query->from('fund_allocations')
                    ->selectRaw('
                *,
                ROW_NUMBER() OVER (
                    PARTITION BY
                        cost_center_id,
                        supplemental_quarter_id,
                        fund_cluster_id,
                        wpf_type_id
                    ORDER BY id ASC
                ) as rn
            ');
            }, 't')
            ->where('rn', 1)
            ->get();

        foreach ($data as $key => $value) {
            FundAllocationBatches::dispatch($value);
        }


        return Command::SUCCESS;
    }
}
