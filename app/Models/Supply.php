<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function categoryItems()
    {
        return $this->belongsTo(CategoryItems::class, 'category_item_id');
    }

    public function categoryGroups()
    {
        return $this->belongsTo(CategoryGroup::class, 'category_group_id');
    }

    public function wfpDetails()
    {
        return $this->hasMany(WfpDetail::class);
    }
}