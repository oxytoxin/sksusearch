<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorQuery extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function reportedSupplies()
    {
        return $this->hasMany(ReportedSupply::class, 'error_query_id');
    }
}
