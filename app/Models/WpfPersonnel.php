<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpfPersonnel extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offices()
    {
        return $this->hasMany(Office::class);
    }

    public function cost_center()
    {
        return $this->belongsTo(CostCenter::class);
    }
}
