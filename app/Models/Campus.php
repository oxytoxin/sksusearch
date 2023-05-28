<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCampus
 */
class Campus extends Model
{
    use HasFactory;

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function offices()
    {
        return $this->hasMany(Office::class);
    }

    public function petty_cash_fund()
    {
        return $this->hasOne(PettyCashFund::class);
    }

    public function vehicle()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function employee_information()
    {
        return $this->hasMany(EmployeeInformation::class);
    }

    public function electricity_meters()
    {
        return $this->hasMany(ElectricityMeter::class);
    }

    public function water_meters()
    {
        return $this->hasMany(WaterMeter::class);
    }

    public function telephone_account_numbers()
    {
        return $this->hasMany(TelephoneAccountNumber::class);
    }

    public function internet_accounts_numbers()
    {
        return $this->hasMany(InternetAccountNumber::class);
    }

}
