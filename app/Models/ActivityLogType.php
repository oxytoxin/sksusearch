<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogType extends Model
{
    use HasFactory;

    const DISBURSEMENT_VOUCHER_LOG = 1;

    public function activity_logs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
