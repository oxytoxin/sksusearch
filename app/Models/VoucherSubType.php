<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherSubType extends Model
{
    use HasFactory;

    public function voucher_types()
    {
        return $this->belongsTo(VoucherType::class);
    }
}
