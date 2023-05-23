<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPhilippineProvince
 */
class PhilippineProvince extends Model
{
    use HasFactory;

    public function region()
    {
        return $this->belongsTo(PhilippineRegion::class, 'region_code', 'region_code');
    }
}
