<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperTravelOrder
 */
class TravelOrder extends Model
{
    use HasFactory;

    protected $casts = [
        'date_from' => 'immutable_date',
        'date_to' => 'immutable_date',
        'has_registration' => 'boolean',
        'needs_vehicle' => 'boolean',
    ];

    public static function generateTrackingCode(): string
    {
        return 'to-' . today()->format('y') . '-' . Str::random(8);
    }

    protected function registrationAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function travel_order_type()
    {
        return $this->belongsTo(TravelOrderType::class);
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function applicants()
    {
        return $this
            ->belongsToMany(User::class, 'travel_order_applicants', 'travel_order_id', 'user_id')
            ->wherePivotNull('deleted_at')
            ->withTimestamps();
    }

    public function removed_applicants()
    {
        return $this
            ->belongsToMany(User::class, 'travel_order_applicants', 'travel_order_id', 'user_id')
            ->wherePivotNotNull('deleted_at')
            ->withTimestamps();
    }

    public function signatories()
    {
        return $this->belongsToMany(User::class, 'travel_order_signatories', 'travel_order_id', 'user_id')->withPivot(['id', 'is_approved', 'role'])->withTimestamps();
    }

    public function immediate_supervisors()
    {
        return $this->belongsToMany(User::class, 'travel_order_signatories', 'travel_order_id', 'user_id')->wherePivot('role', 'immediate_supervisor')->withPivot(['id', 'is_approved', 'role'])->withTimestamps();
    }

    public function recommending_approval()
    {
        return $this->belongsToMany(User::class, 'travel_order_signatories', 'travel_order_id', 'user_id')->wherePivot('role', 'recommending_approval')->withPivot(['id', 'is_approved', 'role'])->withTimestamps();
    }

    public function university_president()
    {
        return $this->belongsToMany(User::class, 'travel_order_signatories', 'travel_order_id', 'user_id')->wherePivot('role', 'university_president')->withPivot(['id', 'is_approved', 'role'])->withTimestamps();
    }

    public function sidenotes()
    {
        return $this->morphMany(Sidenote::class, 'sidenoteable');
    }

    public function disbursement_vouchers()
    {
        return $this->hasMany(DisbursementVoucher::class);
    }

    public function destination(): Attribute
    {
        $this->load(['philippine_region', 'philippine_province', 'philippine_city']);
        $destination = [];
        if ($this->philippine_region) {
            $destination[] = $this->philippine_region->region_description;
        }
        if ($this->philippine_province) {
            $destination[] = $this->philippine_province->province_description;
        }
        if ($this->philippine_city) {
            $destination[] = $this->philippine_city->city_municipality_description;
        }
        if ($this->other_details) {
            $destination[] = $this->other_details;
        }

        return Attribute::make(
            get: fn () => implode(', ', $destination),
        );
    }

    public function philippine_region()
    {
        return $this->belongsTo(PhilippineRegion::class);
    }

    public function philippine_province()
    {
        return $this->belongsTo(PhilippineProvince::class);
    }

    public function philippine_city()
    {
        return $this->belongsTo(PhilippineCity::class);
    }

    public function request_schedule()
    {
        return $this->hasOne(RequestSchedule::class);
    }

    public function scopeApproved($query)
    {
        $query->whereDoesntHave('signatories', function ($query) {
            $query->where('is_approved', false);
        });
    }
}
