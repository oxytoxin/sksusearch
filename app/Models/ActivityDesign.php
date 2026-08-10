<?php

    namespace App\Models;

    use App\Enums\ActivityDesignStatus;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Auth;

    class ActivityDesign extends Model
    {
        use HasFactory;

        protected $casts = [
            'start_date' => 'immutable_date',
            'end_date' => 'immutable_date',
            'status' => ActivityDesignStatus::class,
        ];

        public function disbursement_vouchers()
        {
            return $this->hasMany(DisbursementVoucher::class);
        }


        public function signatories()
        {
            return $this->hasManyThrough(ActivityDesignSignatory::class, ActivityDesignSignatoryGroup::class);
        }

        public function signatory_groups()
        {
            return $this->hasMany(ActivityDesignSignatoryGroup::class);
        }

        public function particulars()
        {
            return $this->hasMany(ActivityDesignParticular::class);
        }

        public function participants()
        {
            return $this->hasMany(ActivityDesignParticipant::class);
        }

        public function coordinators()
        {
            return $this->hasMany(ActivityDesignCoordinator::class);
        }

        public function attachments()
        {
            return $this->morphMany(Attachment::class, 'attachable');
        }

        public function fund_cluster()
        {
            return $this->belongsTo(FundCluster::class);
        }

        public function requisitioner()
        {
            return $this->belongsTo(User::class, 'requisitioner_id');
        }

        public function activity_design_logs()
        {
            return $this->hasMany(ActivityDesignLog::class);
        }

        protected static function booted()
        {
            static::creating(function ($activityDesign) {
                $activityDesign->requisitioner_id = Auth::id();
            });

            static::created(function ($activityDesign) {
                $activityDesign->total_amount = $activityDesign->particulars()->sum('amount');
                $activityDesign->save();
            });
        }
    }
