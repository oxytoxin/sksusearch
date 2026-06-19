<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchTransmittal extends Model
{
    protected $guarded = [];

    protected $casts = [
        'forwarded_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(BatchTransmittalItem::class);
    }

    public function office_group()
    {
        return $this->belongsTo(OfficeGroup::class);
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function forwarded_by_user()
    {
        return $this->belongsTo(User::class, 'forwarded_by');
    }

    public function received_by_user()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public static function generateSerialNumber(int $officeGroupId): int
    {
        return (static::where('office_group_id', $officeGroupId)->max('serial_number') ?? 0) + 1;
    }
}
