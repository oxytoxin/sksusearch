<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        return 'lr-'.today()->format('y').'-'.Str::random(8);
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

    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    public function signatoryApproval()
    {
        return $this->morphOne(Approval::class, 'approvable')->where('role', 'signatory');
    }

    public function accountantApproval()
    {
        return $this->morphOne(Approval::class, 'approvable')->where('role', 'accountant');
    }

    public function recordSignatoryApproval(?int $approvedByOicId = null, $approvedAt = null): Approval
    {
        return $this->recordApproval('signatory', $this->signatory_id, $approvedByOicId, $approvedAt);
    }

    public function recordAccountantApproval(int $slotOwnerId, ?int $approvedByOicId = null, $approvedAt = null): Approval
    {
        return $this->recordApproval('accountant', $slotOwnerId, $approvedByOicId, $approvedAt);
    }

    private function recordApproval(string $role, int $slotOwnerId, ?int $approvedByOicId = null, $approvedAt = null): Approval
    {
        return $this->approvals()->updateOrCreate(
            ['role' => $role],
            [
                'user_id' => $slotOwnerId,
                'approved_at' => $approvedAt ?? now(),
                'approved_by_oic_id' => $approvedByOicId,
            ],
        );
    }
}
