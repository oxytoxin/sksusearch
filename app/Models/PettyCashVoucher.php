<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

/**
 * @mixin IdeHelperPettyCashVoucher
 */
class PettyCashVoucher extends Model
{
    use HasFactory;

    protected $casts = [
        'particulars' => 'array',
        'pcv_date' => 'immutable_datetime',
        'is_liquidated' => 'boolean',
    ];

    protected function amountGranted(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function amountPaid(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function netAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->amount_granted - $this->amount_paid,
        );
    }

    public function petty_cash_fund_records()
    {
        return $this->morphMany(PettyCashFundRecord::class, 'recordable');
    }

    public static function generateTrackingNumber($pcf)
    {
        return $pcf->campus->campus_code . '-' . today()->format('Y') . '-' . (PettyCashVoucher::wherePettyCashFundId($pcf->id)->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])->count() + 1);
    }

    public function petty_cash_fund()
    {
        return $this->belongsTo(PettyCashFund::class);
    }

    public function fund_cluster()
    {
        return $this->belongsTo(FundCluster::class);
    }

    public function requisitioner()
    {
        return $this->belongsTo(User::class, 'requisitioner_id');
    }

    public function signatory()
    {
        return $this->belongsTo(User::class, 'signatory_id');
    }

    public function custodian()
    {
        return $this->belongsTo(User::class, 'custodian_id');
    }
}
