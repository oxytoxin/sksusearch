<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLiquidationReportStep
 */
class LiquidationReportStep extends Model
{
    use HasFactory;

    protected $casts = [
        'enabled' => 'boolean'
    ];

    public function current_liquidation_reports()
    {
        return $this->hasMany(LiquidationReport::class, 'current_step_id');
    }

    public function previous_liquidation_reports()
    {
        return $this->hasMany(LiquidationReport::class, 'previous_step_id');
    }

    public function nextStep(): Attribute
    {
        return new Attribute(get: fn () => LiquidationReportStep::whereEnabled(true)->where('id', '>', $this->id)->first());
    }

    public function previousStep(): Attribute
    {
        return new Attribute(get: fn () => LiquidationReportStep::whereEnabled(true)->where('id', '<', $this->id)->latest('id')->first());
    }

    public function firstStepInGroup(): Attribute
    {
        return new Attribute(get: fn () => LiquidationReportStep::whereEnabled(true)->where('id', '<', $this->id)->where('process', 'Forwarded to')->latest('id')->first());
    }

    public function office_group()
    {
        return $this->belongsTo(OfficeGroup::class);
    }
}
