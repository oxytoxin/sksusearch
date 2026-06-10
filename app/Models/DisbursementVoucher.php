<?php

    namespace App\Models;

    use Str;
    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;

    /**
     * @mixin IdeHelperDisbursementVoucher
     */
    class DisbursementVoucher extends Model
    {
        use HasFactory;

        protected $casts = [
            'closed_at' => 'immutable_date',
            'submitted_at' => 'immutable_date',
            'documents_verified_at' => 'immutable_datetime',
            'due_date' => 'immutable_date',
            'journal_date' => 'immutable_date',
            'certified_by_accountant' => 'boolean',
            'for_cancellation' => 'boolean',
            'cancelled_at' => 'immutable_datetime',
            'draft' => 'array',
            'related_documents' => 'array',
            'other_details' => 'array',
        ];

        protected function totalAmount(): Attribute
        {
            return Attribute::make(
                get: fn($value) => $this->disbursement_voucher_particulars->sum('amount'),
            );
        }

        protected function totalSum(): Attribute
        {
            return Attribute::make(
                get: fn($value) => $this->disbursement_voucher_particulars->sum('final_amount'),
            );
        }

        public function petty_cash_fund_records()
        {
            return $this->morphMany(PettyCashFundRecord::class, 'recordable');
        }

        public static function generateTrackingNumber()
        {
            return 'dv-'.today()->format('y').'-'.Str::random(8);
        }

        public function voucher_subtype()
        {
            return $this->belongsTo(VoucherSubType::class);
        }

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

        public function mop()
        {
            return $this->belongsTo(Mop::class);
        }

        public function current_step()
        {
            return $this->belongsTo(DisbursementVoucherStep::class, 'current_step_id');
        }

        public function previous_step()
        {
            return $this->belongsTo(DisbursementVoucherStep::class, 'previous_step_id');
        }

        public function pending_return_step()
        {
            return $this->belongsTo(DisbursementVoucherStep::class, 'pending_return_step_id');
        }

        public function disbursement_voucher_particulars()
        {
            return $this->hasMany(DisbursementVoucherParticular::class);
        }

        public function fund_cluster()
        {
            return $this->belongsTo(FundCluster::class);
        }

        public function activity_logs()
        {
            return $this->morphMany(ActivityLog::class, 'loggable');
        }

        public function dv_adjustments()
        {
            return $this->hasMany(DvAdjustment::class);
        }

        public function scanned_documents()
        {
            return $this->morphMany(ScannedDocument::class, 'documentable');
        }

        public function liquidation_report()
        {
            return $this->hasOne(LiquidationReport::class);
        }

        public function travel_completed_certificate()
        {
            return $this->hasOne(TravelCompletedCertificate::class);
        }

    public function cash_advance_reminder()
    {
        return $this->hasOne(CaReminderStep::class);
    }
    public function ca_reminder_steps()
    {
        return $this->hasOne(CaReminderStep::class);
    }

        public function totalSumDisbursementVoucherParticular()
        {
            return $this->disbursement_voucher_particulars->sum('final_amount');
        }

        public function daysOutstanding()
        {
            $cashAdvanceReminder = $this->cash_advance_reminder;
            if (!$cashAdvanceReminder) {
                return null;
            }
            $endDate = $cashAdvanceReminder->voucher_end_date;
            return $endDate ? Carbon::now()->diffInDays(Carbon::parse($endDate)) : null;
        }

        /**
         * Normalize related_documents JSON into a unified item collection regardless of stored format.
         *
         * Returns a collection of: ['document' => string, 'status' => 'required'|'not_required'|'not_applicable', 'remarks' => ?string]
         *
         * Supports two storage formats:
         *  - NEW (3-state): ['items' => [...], 'remarks' => '...']
         *  - LEGACY (binary): ['required_documents' => [...], 'verified_documents' => [...], 'remarks' => '...']
         */
        public function getRelatedDocumentItems()
        {
            $data = $this->related_documents;
            if (blank($data)) {
                return collect();
            }

            // New format
            if (isset($data['items']) && is_array($data['items'])) {
                return collect($data['items'])->map(fn($item) => [
                    'document' => $item['document'] ?? '',
                    'status' => $item['status'] ?? 'required',
                    'remarks' => $item['remarks'] ?? null,
                ]);
            }

            // Legacy format — checked = required (verified), unchecked = not_required
            $required = $data['required_documents'] ?? ($this->voucher_subtype?->related_documents_list?->documents ?? []);
            $verified = $data['verified_documents'] ?? [];
            return collect($required)->map(fn($doc) => [
                'document' => $doc,
                'status' => in_array($doc, $verified) ? 'required' : 'not_required',
                'remarks' => null,
            ]);
        }

        /**
         * Returns the general remarks regardless of storage format.
         */
        public function getRelatedDocumentsGeneralRemarks(): ?string
        {
            return $this->related_documents['remarks'] ?? null;
        }

        /**
         * Determines whether the related_documents verification is complete enough to allow forwarding.
         * - All items must have a status set
         * - No item may be marked 'not_applicable' (those must be returned, not forwarded)
         */
        public function hasCompletedRelatedDocumentsVerification(): bool
        {
            if (blank($this->related_documents)) {
                return false;
            }

            // Legacy records are considered complete if they have any data saved
            if (!isset($this->related_documents['items'])) {
                return true;
            }

            $items = $this->getRelatedDocumentItems();
            if ($items->isEmpty()) {
                return false;
            }

            foreach ($items as $item) {
                if (blank($item['status'])) {
                    return false;
                }
                // Not Applicable means the DV is incomplete — it must be returned, not forwarded.
                if ($item['status'] === 'not_applicable') {
                    return false;
                }
            }
            return true;
        }

        public function hasNotApplicableRelatedDocuments(): bool
        {
            $items = $this->getRelatedDocumentItems();
            foreach ($items as $item) {
                if (($item['status'] ?? null) === 'not_applicable') {
                    return true;
                }
            }
            return false;
        }
    }
