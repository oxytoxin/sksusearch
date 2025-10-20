<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    /**
     * @mixin IdeHelperItineraryEntry
     */
    class ItineraryEntry extends Model
    {
        use HasFactory;

        protected $casts = [
            'date' => 'date:Y-m-d',
            'date' => 'immutable_date',
            'departure_time' => 'immutable_datetime',
            'arrival_time' => 'immutable_datetime',
        ];


        public function itinerary()
        {
            return $this->belongsTo(Itinerary::class);
        }

        public function mot()
        {
            return $this->belongsTo(Mot::class);
        }
    }
