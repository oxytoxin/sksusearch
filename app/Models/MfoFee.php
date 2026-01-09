<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class MfoFee extends Model
    {
        use HasFactory;

        protected $guarded = [];

        public function mfo()
        {
            return $this->belongsTo(MFO::class, 'm_f_o_s_id');
        }

        public function fundClusterWFP()
        {
            return $this->belongsTo(FundCluster::class, 'fund_cluster_id');
        }

        public function costCenter()
        {
            return $this->belongsTo(CostCenter::class, 'cost_center_id');
        }
    }
