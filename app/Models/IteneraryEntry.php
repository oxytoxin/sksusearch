<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IteneraryEntry extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'immutable_date',
        'departure_time' => 'immutable_datetime',
        'arrival_time' => 'immutable_datetime',
    ];

    protected function transportationExpenses(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function otherExpenses(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function itenerary()
    {
        return $this->belongsTo(Itenerary::class);
    }

    public function mot()
    {
        return $this->belongsTo(Mot::class);
    }
}
