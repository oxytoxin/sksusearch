<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;

    protected $casts = [
        'coverage' => 'array',
    ];

    public function travel_order()
    {
        return $this->belongsTo(TravelOrder::class);
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
