<?php

namespace App\Jobs;

use App\Models\FundAllocation;
use App\Models\Wfp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AttachWfpIdToFundDraftItems implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public FundAllocation $fundAllocation)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            $wfp = Wfp::where('wpf_type_id', $this->fundAllocation->wpf_type_id)
                ->where('supplemental_quarter_id', $this->fundAllocation->supplemental_quarter_id)
                ->where('cost_center_id', $this->fundAllocation->cost_center_id)
                ->where('fund_cluster_id', $this->fundAllocation->fund_cluster_id)
                ->first();

            foreach ($this->fundAllocation->fundDrafts->first()->draft_items as $items) {
                DB::table('fund_draft_items')
                    ->where('id', $items->id)
                    ->update([
                        'wfp_id' => $wfp->id
                    ]);
            }
    }
}
