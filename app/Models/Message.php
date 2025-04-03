<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    public function messageable()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function receiver()
{
    return $this->belongsTo(User::class, 'receiver_id');
}
}
