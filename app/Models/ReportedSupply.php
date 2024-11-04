<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedSupply extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function errorQuery()
    {
        return $this->belongsTo(ErrorQuery::class, 'error_query_id');
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(ReportSupplyReplies::class, 'reported_supply_id');
    }
}
