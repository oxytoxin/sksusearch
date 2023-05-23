<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperElectricityMeter
 */
class ElectricityMeter extends Model
{
    use HasFactory;

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
