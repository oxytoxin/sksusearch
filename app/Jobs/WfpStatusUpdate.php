<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WfpStatusUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $wfp)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $status = 'allocated';
        if($this->wfp->is_approved == 1) {
            $status = 'approved';
        }elseif($this->wfp->is_approved == 0 && $this->wfp->is_draft == 0) {
            $status = 'submitted';
        }elseif($this->wfp->is_approved == 0 && $this->wfp->is_draft == 1) {
            $status = 'draft';
        }elseif($this->wfp->is_approved == 500) {
            $status = 'for_modification';
        }
        $this->wfp->update([
            'approved_at' => $this->wfp->updated_at,
            'status_last_updated_at' => $this->wfp->updated_at,
            'transaction_status' => $status
        ]);
    }
}
