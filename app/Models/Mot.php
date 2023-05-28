<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMot
 */
class Mot extends Model
{
    use HasFactory;

    public function itinerary_entries()
    {
        return $this->hasMany(ItineraryEntry::class);
    }
}
