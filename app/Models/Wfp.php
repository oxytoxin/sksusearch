<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wfp extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function wfpDetails()
    {
        return $this->hasMany(WfpDetail::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function wfpType()
    {
        return $this->belongsTo(WpfType::class, 'wpf_type_id', 'id');
    }

    public function fundClusterWfp()
    {
        return $this->belongsTo(FundClusterWFP::class, 'fund_cluster_w_f_p_s_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wfpApprovalRemarks()
    {
        return $this->hasMany(WfpApprovalRemark::class, 'wfps_id');
    }

}
