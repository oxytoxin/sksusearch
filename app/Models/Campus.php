<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use HasFactory;

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function offices()
    {
        return $this->hasMany(Office::class);
    }

    public function petty_cash_fund()
    {
        return $this->hasOne(PettyCashFund::class);
    }
}
