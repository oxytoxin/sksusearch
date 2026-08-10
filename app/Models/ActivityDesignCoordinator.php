<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityDesignCoordinator extends Model
{
    public function activity_design()
    {
        return $this->belongsTo(ActivityDesign::class);
    }
}
