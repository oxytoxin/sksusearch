<?php

namespace App\Models;

use App\Enums\ActivityDesignSignatoryGroupStatus;
use App\Enums\ActivityDesignSignatoryStatus;
use App\Enums\ActivityDesignStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ActivityDesignSignatory extends Model
{
    protected $casts = [
        'status' => ActivityDesignSignatoryStatus::class,
    ];

    #[Scope]
    protected function toSign(Builder $query, $signatory_id): void
    {
        $query->where('signatory_id', $signatory_id)
            ->where('activity_design_signatories.status', ActivityDesignSignatoryStatus::IN_APPROVAL)
            ->whereHas('signatory_group', function (Builder $query) {
                $query
                    ->where('status', ActivityDesignSignatoryGroupStatus::IN_APPROVAL)
                    ->whereRelation('activity_design', 'status', ActivityDesignStatus::IN_APPROVAL);
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'signatory_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function signatory_group()
    {
        return $this->belongsTo(ActivityDesignSignatoryGroup::class, 'activity_design_signatory_group_id');
    }

    public function activity_design_logs()
    {
        return $this->hasMany(ActivityDesignLog::class, 'signatory_id');
    }

    protected static function booted()
    {
        static::updated(function (ActivityDesignSignatory $signatory) {});
    }
}
