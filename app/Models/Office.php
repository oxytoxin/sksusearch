<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    public function employee_informations()
    {
        return $this->hasMany(EmployeeInformation::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
