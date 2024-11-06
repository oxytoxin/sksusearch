<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundDraftAmount extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function fundDraft()
    {
        return $this->belongsTo(FundDraft::class, 'fund_draft_id');
    }
}
