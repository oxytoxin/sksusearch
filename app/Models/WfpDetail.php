<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WfpDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function wfp()
    {
        return $this->belongsTo(Wfp::class);
    }

    public function budgetCategory()
    {
        return $this->belongsTo(BudgetCategory::class);
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function categoryGroup()
    {
        return $this->belongsTo(CategoryGroup::class);
    }

    public function categoryItem()
    {
        return $this->belongsTo(CategoryItems::class);
    }
}
