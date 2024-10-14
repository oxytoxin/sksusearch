<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WfpRequestedSupply extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categoryItems()
    {
        return $this->belongsTo(CategoryItems::class, 'category_item_id');
    }

    public function categoryGroups()
    {
        return $this->belongsTo(CategoryGroup::class, 'category_group_id');
    }

    public function wfpRequestTimeline()
    {
        return $this->hasMany(WfpRequestTimeline::class, 'wfp_request_id');
    }
}
