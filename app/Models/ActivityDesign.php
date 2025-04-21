<?php

namespace App\Models;

use App\Enums\ActivityDesignStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityDesign extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'immutable_date',
        'end_date' => 'immutable_date',
        'status' => ActivityDesignStatus::class,
    ];
}
