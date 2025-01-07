<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function mfo()
    {
        return $this->belongsTo(MFO::class, 'm_f_o_s_id', 'id');
    }

    public function fundClusterWFP()
    {
        return $this->belongsTo(FundClusterWFP::class, 'fund_cluster_w_f_p_s_id', 'id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function fundAllocations()
    {
        return $this->hasMany(FundAllocation::class);
    }

    public function mfoFee()
    {
        return $this->belongsTo(MfoFee::class, 'mfo_fee_id', 'id');
    }

    public function wfp()
    {
        return $this->hasOne(Wfp::class);
    }

    public function wpfPersonnel()
    {
        return $this->hasMany(WpfPersonnel::class);
    }


}
