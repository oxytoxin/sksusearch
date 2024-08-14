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
}
