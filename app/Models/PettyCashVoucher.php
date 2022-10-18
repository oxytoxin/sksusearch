<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

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

    public function petty_cash_fund_records()
    {
        return $this->morphMany(PettyCashFundRecord::class, 'recordable');
    }

    public static function generateTrackingNumber()
    {
        return 'pcv-' . today()->format('y') . '-' . Str::random(8);
    }

    public function petty_cash_fund()
    {
        return $this->belongsTo(PettyCashFund::class);
    }

    public function requisitioner()
    {
        return $this->belongsTo(User::class, 'requisitioner_id');
    }

    public function signatory()
    {
        return $this->belongsTo(User::class, 'signatory_id');
    }
}
