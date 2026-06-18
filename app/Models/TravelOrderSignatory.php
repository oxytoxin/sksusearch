<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TravelOrderSignatory extends Pivot
{
    protected $table = 'travel_order_signatories';

    public $incrementing = true;

    protected $casts = [
        'approved_at' => 'immutable_datetime',
    ];

    public function oic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_oic_id');
    }

    public function signer(?User $slotOwner = null): ?User
    {
        return $this->approved_by_oic_id ? $this->oic : $slotOwner;
    }

    public function wasSignedByOic(): bool
    {
        return filled($this->approved_by_oic_id);
    }
}
