<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaReminderStepHistory extends Model
{
    use HasFactory;

    public function caReminderStep()
    {
        return $this->belongsTo(CaReminderStep::class);
    }
}
