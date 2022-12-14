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

    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function heads()
    {
        return $this->hasOne(EmployeeInformation::class, 'id', 'head_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function officers_in_charge()
    {
        return $this->belongsToMany(User::class, 'office_user', 'office_id', 'user_id');
    }

    public function office_group()
    {
        return $this->belongsTo(OfficeGroup::class);
    }
}
