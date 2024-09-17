<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundClusterWFP extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function costCenters()
    {
        return $this->hasMany(CostCenter::class, 'fund_cluster_w_f_p_s_id', 'id');
    }

    public function fundAllocations()
    {
        return $this->hasMany(FundAllocation::class, 'fund_cluster_w_f_p_s_id', 'id');
    }

    public function mfoFees()
    {
        return $this->hasMany(MfoFee::class, 'fund_cluster_w_f_p_s_id', 'id');
    }

    public function wfps()
    {
        return $this->hasMany(Wfp::class, 'fund_cluster_w_f_p_s_id', 'id');
    }
}
