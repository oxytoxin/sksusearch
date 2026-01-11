<?php

namespace App\Jobs;

use App\Models\FundAllocation;
use App\Models\FundAllocationBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class FundAllocationBatches implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $fundAllocation)
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $batch = FundAllocationBatch::create([
                'cost_center_id' => $this->fundAllocation->cost_center_id,
                'wfp_type_id' => $this->fundAllocation->wpf_type_id,
                'fund_cluster_id' => $this->fundAllocation->fund_cluster_id,
                'supplemental_quarter_id' => $this->fundAllocation->supplemental_quarter_id,
                'is_supplemental' => is_null($this->fundAllocation->supplemental_quarter_id) ? 0 : 1,
                'is_locked' => $this->fundAllocation->is_locked,
                'created_at' => $this->fundAllocation->created_at,
                'updated_at' => $this->fundAllocation->updated_at
        ]);

        FundAllocation::where('cost_center_id', $this->fundAllocation->cost_center_id)
            ->where('wpf_type_id', $this->fundAllocation->wpf_type_id)
            ->where('fund_cluster_id', $this->fundAllocation->fund_cluster_id)
            ->where('supplemental_quarter_id', $this->fundAllocation->supplemental_quarter_id)
            ->where('is_supplemental', is_null($this->fundAllocation->supplemental_quarter_id) ? 0 : 1)
             ->update(['fund_allocation_batch_id' => $batch->id]);
    }
}
