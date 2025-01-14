<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItemBudget extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function budgetCategory()
    {
        return $this->belongsTo(BudgetCategory::class);
    }

    // public function scopeActiveCategoryItems($query)
    // {
    //     return $query->where('is_active', 1);
    // }

    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }

    public function wfpDetails()
    {
        return $this->hasMany(WfpDetail::class);
    }
}
