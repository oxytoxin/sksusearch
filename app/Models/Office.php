<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    public function employee_information()
    {
        return $this->hasMany(EmployeeInformation::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function office_group()
    {
        return $this->belongsTo(OfficeGroup::class);
    }
}
