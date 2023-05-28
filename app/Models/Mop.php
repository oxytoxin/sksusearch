<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMop
 */
class Mop extends Model
{
    use HasFactory;

    public function disbursement_vouchers()
    {
        return $this->hasMany(DisbursementVoucher::class);
    }
}
