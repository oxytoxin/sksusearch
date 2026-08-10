<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityDesignParticular extends Model
{
    public function activity_design()
    {
        return $this->belongsTo(ActivityDesign::class);
    }

    protected static function booted()
    {
        static::created(function ($activityDesignParticular) {
            $activityDesignParticular->activity_design->total_amount = $activityDesignParticular->activity_design->particulars()->sum('amount');
            $activityDesignParticular->activity_design->save();
        });
        static::updated(function ($activityDesignParticular) {
            $activityDesignParticular->activity_design->total_amount = $activityDesignParticular->activity_design->particulars()->sum('amount');
            $activityDesignParticular->activity_design->save();
        });
    }
}
