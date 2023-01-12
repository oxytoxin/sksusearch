<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessages extends Model
{
    use HasFactory;

    
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function chat_group()
    {
        return $this->hasOne(ChatGroup::class);
    }
}
