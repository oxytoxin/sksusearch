<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTravelOrderType
 */
class TravelOrderType extends Model
{
    use HasFactory;

    const OFFICIAL_BUSINESS = 1;

    const OFFICIAL_TIME = 2;
}
