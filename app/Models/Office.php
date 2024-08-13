<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOffice
 */
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

    public function head_position()
    {
        return $this->belongsTo(Position::class, 'head_position_id');
    }

    public function office_group()
    {
        return $this->belongsTo(OfficeGroup::class);
    }

    public function head_employee()
    {
        return $this->hasOne(EmployeeInformation::class)
            ->where('position_id', $this->head_position_id);
    }

    public function cost_centers()
    {
        return $this->hasMany(CostCenter::class);
    }

    public function wpf_personnels()
    {
            return $this->belongsTo(WpfPersonnel::class);
    }
}
