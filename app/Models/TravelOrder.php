<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TravelOrder extends Model
{
    use HasFactory;

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'has_registration' => 'boolean',
    ];

    public static function generateTrackingCode(): string
    {
        return Str::uuid()->toString();
    }

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

    public function applicants()
    {
        return $this->belongsToMany(User::class, 'travel_order_applicants', 'travel_order_id', 'user_id');
    }

    public function signatories()
    {
        return $this->belongsToMany(User::class, 'travel_order_signatories', 'travel_order_id', 'user_id')->withPivot('is_approved');
    }

    public function sidenotes()
    {
        return $this->morphMany(Sidenotes::class, 'sidenoteable');
    }
}
