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

    public function fuel_requisitions()
    {
        return $this->hasManyThrough(
            FuelRequisition::class,
            RequestSchedule::class,
            'vehicle_id',
            'request_schedule_id',
            'id',
            'id'
        );
    }

    public function getLatestOdometerReadingAttribute(): ?int
    {
        return $this->fuel_requisitions()
            ->whereNotNull('odometer_reading')
            ->latest('fuel_requisitions.created_at')
            ->value('odometer_reading');
    }
}
