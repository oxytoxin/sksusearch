<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelRequisition extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function requested_by_employee()
    {
        return $this->belongsTo(EmployeeInformation::class, 'requested_by');
    }

    public function request_schedule()
    {
        return $this->belongsTo(RequestSchedule::class);
    }
}
