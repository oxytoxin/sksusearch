<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperVehicle
 */
class Vehicle extends Model
{
    use HasFactory;

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function request_schedule()
    {
        return $this->hasOne(RequestSchedule::class);
    }

    public function date_and_times()
    {
        return $this->hasMany(RequestScheduleTimeAndDate::class);
    }
}
