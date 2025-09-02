<?php

namespace App\Models;

use App\Models\CostCenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FundAllocation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function wpfType()
    {
        return $this->belongsTo(WpfType::class, 'wpf_type_id', 'id');
    }

    public function fundClusterWFP()
    {
        return $this->belongsTo(FundClusterWFP::class, 'fund_cluster_w_f_p_s_id', 'id');
    }

    public function categoryGroup()
    {
        return $this->belongsTo(CategoryGroup::class, 'category_group_id', 'id');
    }

    public function fundDrafts()
    {
        return $this->hasMany(FundDraft::class, 'fund_allocation_id');
    }

    public function supplementalQuarter()
    {
        return $this->belongsTo(SupplementalQuarter::class);
    }
}
