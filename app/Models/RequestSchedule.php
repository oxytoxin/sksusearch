<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRequestSchedule
 */
class RequestSchedule extends Model
{
    use HasFactory;

    public function applicants()
    {
        return $this->belongsToMany(User::class, 'request_applicants', 'request_schedule_id', 'user_id')->withTimestamps();
    }

    public function requested_by()
    {
        return $this->belongsTo(User::class, 'requested_by_id');
    }

    public function driver()
    {
        return $this->belongsTo(EmployeeInformation::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function philippine_region()
    {
        return $this->belongsTo(PhilippineRegion::class);
    }

    public function philippine_province()
    {
        return $this->belongsTo(PhilippineProvince::class);
    }

    public function philippine_city()
    {
        return $this->belongsTo(PhilippineCity::class);
    }

    public function date_and_times()
    {
        return $this->hasMany(RequestScheduleTimeAndDate::class);
    }
}
