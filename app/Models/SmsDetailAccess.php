<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsDetailAccess extends Model
{
    protected $table = 'sms_detail_access';

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
