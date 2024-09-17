<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpfType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function fundAllocations()
    {
        return $this->hasMany(FundAllocation::class);
    }

    public function wfps()
    {
        return $this->hasMany(Wfp::class, 'wpf_type_id', 'id');
    }
}
