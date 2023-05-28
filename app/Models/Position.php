<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPosition
 */
class Position extends Model
{
    use HasFactory;

    public function employee_information()
    {
        return $this->hasMany(EmployeeInformation::class);
    }
}
