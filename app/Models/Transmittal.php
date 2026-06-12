<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Str;

/**
 * A transmittal batches one or more Disbursement Vouchers for hand-off to another
 * office, with a printable form and an acknowledgment receipt.
 *
 * - Batch transmittal  = several DVs (item 3.1 / 3.2).
 * - Individual transmittal = a single DV (item 3.3).
 * Both use the same record; the acknowledgment receipt adapts to the item count.
 */
class Transmittal extends Model
{
    use HasFactory;

    protected $fillable = [
        'transmittal_number',
        'recipient',
        'remarks',
        'office_group_id',
        'prepared_by',
        'acknowledged_at',
        'acknowledged_by',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];

    /**
     * Mirrors DisbursementVoucher::generateTrackingNumber() — prefix + 2-digit
     * year + 8-char random string, e.g. tr-26-aB3xKm9P.
     */
    public static function generateTransmittalNumber()
    {
        return 'tr-' . today()->format('y') . '-' . Str::random(8);
    }

    public function disbursement_vouchers()
    {
        return $this->belongsToMany(
            DisbursementVoucher::class,
            'transmittal_disbursement_vouchers',
            'transmittal_id',
            'disbursement_voucher_id'
        )->withTimestamps();
    }

    public function prepared_by_user()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function getIsAcknowledgedAttribute()
    {
        return filled($this->acknowledged_at);
    }
}
