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

    public function travel_order_type()
    {
        return $this->belongsTo(TravelOrderType::class);
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function applicants()
    {
        return $this->belongsToMany(User::class, 'travel_order_applicants', 'travel_order_id', 'user_id')->withTimestamps();
    }

    public function signatories()
    {
        return $this->belongsToMany(User::class, 'travel_order_signatories', 'travel_order_id', 'user_id')->withPivot('is_approved')->withTimestamps();
    }

    public function sidenotes()
    {
        return $this->morphMany(Sidenote::class, 'sidenoteable');
    }

    public function philippine_region()
    {
        return $this->belongsTo(PhilippineRegion::class);
    }

    public function philippine_province()
    {
        return $this->belongsTo(PhilippineProvince::class);
    }

    public function philippine_city()
    {
        return $this->belongsTo(PhilippineCity::class);
    }

    public function scopeApproved($query)
    {
        $query->whereDoesntHave('signatories', function ($query) {
            $query->where('is_approved', false);
        });
    }
}
