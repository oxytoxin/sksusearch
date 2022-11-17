<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestSchedule extends Model
{
    use HasFactory; 

    public function applicants()
    {
        return $this->belongsToMany(User::class, 'request_applicants', 'request_schedule_id', 'user_id')->withTimestamps();
    }
}
