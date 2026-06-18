<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Polymorphic per-role sign-off record (DV + Liquidation Reports).
 *
 * `user_id` is the slot owner whose official name belongs in the printed signatory
 * field. `approved_by_oic_id` is the user whose e-signature was actually applied.
 */
class Approval extends Model
{
    use HasFactory;

    protected $casts = [
        'approved_at' => 'immutable_datetime',
    ];

    public function approvable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function oic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_oic_id');
    }

    public function signer(): ?User
    {
        return $this->approved_by_oic_id ? $this->oic : $this->user;
    }

    public function signerSignature(): ?string
    {
        return $this->signer()?->signature?->content;
    }

    public function wasSignedByOic(): bool
    {
        return filled($this->approved_by_oic_id);
    }

    public function getEsignNameAttribute(): ?string
    {
        return $this->displayName($this->signer());
    }

    public function getSignatoryNameAttribute(): ?string
    {
        return $this->displayName($this->user);
    }

    private function displayName(?User $user): ?string
    {
        return $user?->employee_information?->full_name ?? $user?->name;
    }
}
