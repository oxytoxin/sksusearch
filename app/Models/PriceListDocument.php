<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceListDocument extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function fileable()
    {
        return $this->morphTo();
    }
}
