<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->employee_information->full_name).'&color=7F9CF5&background=EBF4FF';
    }

    public function employee_information()
    {
        return $this->hasOne(EmployeeInformation::class);
    }

    public function bond()
    {
        return $this->hasOne(Bond::class);
    }

    public function campus()
    {
        return $this->hasOne(Campus::class);
    }

    public function disbursement_vouchers()
    {
        return $this->hasMany(DisbursementVoucher::class);
    }

    public function disbursement_vouchers_to_sign()
    {
        return $this->hasMany(DisbursementVoucher::class, 'signatory_id');
    }

    public function offices_in_charge()
    {
        return $this->belongsToMany(Office::class, 'office_user', 'user_id', 'office_id');
    }

    public function office_headed()
    {
        return $this->hasOne(Office::class, 'head_id');
    }

    public function office_administered()
    {
        return $this->hasOne(Office::class, 'admin_user_id');
    }

    public function iteneraries()
    {
        return $this->hasMany(Itenerary::class);
    }

    public function travel_order_applications()
    {
        return $this->belongsToMany(TravelOrder::class, 'travel_order_applicants', 'user_id', 'travel_order_id');
    }

    public function travel_order_signatories()
    {
        return $this->belongsToMany(TravelOrder::class, 'travel_order_signatories', 'user_id', 'travel_order_id')->withPivot('is_approved');
    }
}
