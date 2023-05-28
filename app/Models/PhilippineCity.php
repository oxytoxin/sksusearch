<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPhilippineCity
 */
class PhilippineCity extends Model
{
    use HasFactory;

    public function province()
    {
        return $this->belongsTo(PhilippineProvince::class, 'province_code', 'province_code');
    }
}
