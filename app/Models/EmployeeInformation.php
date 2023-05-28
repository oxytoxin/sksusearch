<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperEmployeeInformation
 */
class EmployeeInformation extends Model
{
    protected $casts = [
        'birthday' => 'immutable_date',
    ];

    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function bond()
    {
        return $this->belongsTo(Bond::class);
    }

    public function designation(): Attribute
    {
        $this->load(['position', 'office']);
        $designation = [];
        if ($this->position_id) {
            $designation[] = $this->position->description;
        }
        if ($this->office_id) {
            $designation[] = $this->office->name;
        }
        return Attribute::make(
            get: fn () => implode(' - ', $designation),
        );
    }
}
