<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
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
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->employee_information->full_name) . '&color=7F9CF5&background=EBF4FF';
    }


    protected function name(): Attribute
    {
        return new Attribute(get: fn ($value) => $this->employee_information->full_name);
    }

    public function canAccessFilament(): bool
    {
        return $this->email == 'sksusearch@sksu.edu.ph';
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

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function travel_order_applications()
    {
        return $this->belongsToMany(TravelOrder::class, 'travel_order_applicants', 'user_id', 'travel_order_id')->withTimestamps();
    }

    public function travel_order_signatories()
    {
        return $this->belongsToMany(TravelOrder::class, 'travel_order_signatories', 'user_id', 'travel_order_id')->withPivot('is_approved')->withTimestamps();
    }

    public function sidenotes()
    {
        return $this->hasMany(Sidenote::class);
    }

    public function officers_in_charge()
    {
        return $this->belongsToMany(User::class, 'oic_users', 'user_id', 'oic_id')->withPivot(['id', 'valid_from', 'valid_to'])->withTimestamps();
    }

    public function oic_for_users()
    {
        return $this->belongsToMany(User::class, 'oic_users', 'oic_id', 'user_id')->withPivot(['id', 'valid_from', 'valid_to'])->withTimestamps();
    }

    public function petty_cash_fund()
    {
        return $this->hasOne(PettyCashFund::class, 'custodian_id');
    }

    public function request_applicants()
    {
        return $this->belongsToMany(RequestSchedule::class, 'request_applicants', 'user_id', 'request_schedule_id')->withTimestamps();
    }

    
    public function chat_messages()
    {   
        return $this->belongsToMany(ChatMessages::class, 'chat_messages', 'user_id', 'chat_message_id');
    }
    
    public function group_user()
    {
        return $this->belongsToMany(GroupUser::class, 'group_user', 'user_id', 'group_user_id');

    }
}
