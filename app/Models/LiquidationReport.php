<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperLiquidationReport
 */
class LiquidationReport extends Model
{
    use HasFactory;

    protected $casts = [
        'report_date' => 'immutable_date',
        'signatory_date' => 'immutable_date',
        'journal_date' => 'immutable_date',
        'certified_by_accountant' => 'boolean',
        'cancelled_at' => 'immutable_datetime',
        'particulars' => 'array',
        'refund_particulars' => 'array',
        'draft' => 'array',
        'related_documents' => 'array',
    ];

    public static function generateTrackingNumber()
    {
        return 'lr-' . today()->format('y') . '-' . Str::random(8);
    }

    public function requisitioner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function signatory()
    {
        return $this->belongsTo(User::class, 'signatory_id');
    }

    public function activity_logs()
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function current_step()
    {
        return $this->belongsTo(LiquidationReportStep::class, 'current_step_id');
    }

    public function previous_step()
    {
        return $this->belongsTo(LiquidationReportStep::class, 'previous_step_id');
    }

    public function disbursement_voucher()
    {
        return $this->belongsTo(DisbursementVoucher::class);
    }

    public function travel_completed_certificate()
    {
        return $this->hasOne(TravelCompletedCertificate::class);
    }
}
