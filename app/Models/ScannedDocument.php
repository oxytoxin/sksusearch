<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScannedDocument extends Model
{
    use HasFactory;

    public function documentable()
    {
        return $this->morphTo();
    }
}
