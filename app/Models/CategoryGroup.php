<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryGroup extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }
}
