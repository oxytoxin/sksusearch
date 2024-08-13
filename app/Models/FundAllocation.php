<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundAllocation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }
}
