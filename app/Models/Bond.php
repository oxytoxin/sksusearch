<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperBond
 */
class Bond extends Model
{
    use HasFactory;

    protected $casts = [
        'validity_date' => 'immutable_date',
    ];

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee_information()
    {
        return $this->hasOne(EmployeeInformation::class);
    }
}
