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
        return $this->hasMany(CostCenter::class);
    }
}
