<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperVoucherCategory
 */
class VoucherCategory extends Model
{
    use HasFactory;

    public function voucher_types()
    {
        return $this->hasMany(VoucherType::class);
    }
}
