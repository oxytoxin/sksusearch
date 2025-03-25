<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function employee_information()
    {
        return $this->belongsTo(EmployeeInformation::class);
    }
}
