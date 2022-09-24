<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    use HasFactory;

    public function cash_advances()
    {
        return $this->hasMany(CashAdvance::class);
    }
}
