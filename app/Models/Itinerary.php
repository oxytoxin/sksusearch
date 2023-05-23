<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperItinerary
 */
class Itinerary extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'coverage' => 'array',
        'approved_at' => 'immutable_datetime',
    ];

    public function travel_order()
    {
        return $this->belongsTo(TravelOrder::class);
    }

    public function scopeActualItinerary($query)
    {
        return $query->where('is_actual', true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itinerary_entries()
    {
        return $this->hasMany(ItineraryEntry::class);
    }
}
