<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPhilippineRegion
 */
class PhilippineRegion extends Model
{
    use HasFactory;

    public function dte()
    {
        return $this->hasOne(Dte::class);
    }
}
