<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundDraft extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function fundAllocation()
    {
        return $this->belongsTo(FundAllocation::class, 'fund_allocation_id');
    }

    public function draft_items()
    {
        return $this->hasMany(FundDraftItem::class, 'fund_draft_id');
    }

    public function draft_amounts()
    {
        return $this->hasMany(FundDraftAmount::class, 'fund_draft_id');
    }
}
