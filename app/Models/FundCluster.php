<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class FundCluster extends Model
    {
        use HasFactory;

        protected $guarded = [];

        public function fund_cluster_group(): BelongsTo
        {
            return $this->belongsTo(FundClusterGroup::class);
        }

        public function costCenters(): HasMany
        {
            return $this->hasMany(CostCenter::class, 'fund_cluster_id', 'id');
        }

        public function fundAllocations(): HasMany
        {
            return $this->hasMany(FundAllocation::class, 'fund_cluster_id', 'id');
        }

        public function mfoFees(): HasMany
        {
            return $this->hasMany(MfoFee::class, 'fund_cluster_id', 'id');
        }

        public function wfps(): HasMany
        {
            return $this->hasMany(Wfp::class, 'fund_cluster_id', 'id');
        }
    }
