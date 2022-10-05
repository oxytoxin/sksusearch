<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OicUser extends Model
{
    use HasFactory;

    public function oic()
    {
        return $this->belongsTo(User::class, 'oic_id');
    }

    public function signatory()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
