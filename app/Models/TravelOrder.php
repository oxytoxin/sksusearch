<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    use HasFactory;

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'has_registration' => 'boolean',
    ];

    protected function registrationAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function cash_advances()
    {
        return $this->hasMany(CashAdvance::class);
    }

    public function iteneraries()
    {
        return $this->hasMany(Itenerary::class);
    }
}
