<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WfpRequestTimeline extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wfpRequest()
    {
        return $this->belongsTo(WfpRequestedSupply::class, 'wfp_request_id');
    }
}
