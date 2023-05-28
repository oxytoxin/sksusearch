<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOicUser
 */
class OicUser extends Model
{
    use HasFactory;

    public function oic()
    {
        return $this->belongsTo(User::class, 'oic_id');
    }

    public function signatory()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->where('oic_id', auth()->id())->where('valid_from', '<=', today())
                ->where('valid_to', '>=', today());
        })->orWhere(function ($q) {
            $q->where('oic_id', auth()->id())->where('valid_from', '<=', today())
                ->whereNull('valid_to');
        });
    }
}
