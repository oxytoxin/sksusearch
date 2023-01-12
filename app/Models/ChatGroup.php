<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    use HasFactory;

    
    public function chat_messages()
    {
         return $this->belongsTo(ChatMessage::class);
    }
    
    public function group_user()
    {
        return $this->HasMany(GroupUser::class);
    }
}
