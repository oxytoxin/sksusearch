<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestScheduleTimeAndDate extends Model
{
    use HasFactory;

    public function request_schedule()
    {
        return $this->belongsTo(RequestSchedule::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
