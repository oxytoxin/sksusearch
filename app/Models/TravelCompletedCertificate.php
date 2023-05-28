<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTravelCompletedCertificate
 */
class TravelCompletedCertificate extends Model
{
    use HasFactory;

    protected $casts = [
        'details' => 'array',
    ];

    const DEFAULT = 1;
    const CUTSHORT = 2;
    const EXTENDED = 3;
    const OTHER = 4;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function signatory()
    {
        return $this->belongsTo(User::class, 'signatory_id');
    }

    public function travel_order()
    {
        return $this->belongsTo(TravelOrder::class);
    }

    public function liquidation_report()
    {
        return $this->belongsTo(LiquidationReport::class);
    }

    public function disbursement_voucher()
    {
        return $this->belongsTo(DisbursementVoucher::class);
    }
}
