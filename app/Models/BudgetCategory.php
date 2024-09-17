<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function categoryItems()
    {
        return $this->hasMany(CategoryItems::class);
    }

    public function wfpDetails()
    {
        return $this->hasMany(WfpDetail::class);
    }
}
