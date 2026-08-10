<?php

namespace App\Models;

use App\Enums\ActivityDesignSignatoryGroupStatus;
use Illuminate\Database\Eloquent\Model;

class ActivityDesignSignatoryGroup extends Model
{
    protected $casts = [
        'status' => ActivityDesignSignatoryGroupStatus::class,
    ];

    public function activity_design()
    {
        return $this->belongsTo(ActivityDesign::class);
    }

    public function signatories()
    {
        return $this->hasMany(ActivityDesignSignatory::class);
    }
}
