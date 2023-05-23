<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDisbursementVoucherStep
 */
class DisbursementVoucherStep extends Model
{
    use HasFactory;

    protected $casts = [
        'enabled' => 'boolean'
    ];

    public function current_disbursement_vouchers()
    {
        return $this->hasMany(DisbursementVoucher::class, 'current_step_id');
    }

    public function previous_disbursement_vouchers()
    {
        return $this->hasMany(DisbursementVoucher::class, 'previous_step_id');
    }

    public function office_group()
    {
        return $this->belongsTo(OfficeGroup::class);
    }

    public function nextStep(): Attribute
    {
        return new Attribute(get: fn () => DisbursementVoucherStep::whereEnabled(true)->where('id', '>', $this->id)->first());
    }

    public function previousStep(): Attribute
    {
        return new Attribute(get: fn () => DisbursementVoucherStep::whereEnabled(true)->where('id', '<', $this->id)->latest('id')->first());
    }

    public function firstStepInGroup(): Attribute
    {
        return new Attribute(get: fn () => DisbursementVoucherStep::whereEnabled(true)->where('id', '<', $this->id)->where('process', 'Forwarded to')->latest('id')->first());
    }
}
