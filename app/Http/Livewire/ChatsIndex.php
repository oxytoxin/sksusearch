<?php

namespace App\Http\Livewire;

use App\Models\ChatGroup;
use App\Models\GroupUser;
use Livewire\Component;

class ChatsIndex extends Component
{

    //mount
    public $chat_group;
    public $messages;
    public $chats;
    public $user;

    public function mount($chat_group_uuid)
    {
        if ($chat_group_uuid != null || $chat_group_uuid != "") {
            $this->chat_group = ChatGroup::where('uuid', $chat_group_uuid)->first();
            // $this->messages = $this->chat_group->messages;
        }else{
            $this->chat_group = null;
        }
        $this->user = auth()->user();
    }
    public function render()
    {
        $temp = GroupUser::where('user_id', auth()->user()->id)->get('chat_group_id')->groupBy('chat_group_id');
        $this->chats = ChatGroup::whereIn('id', $temp)->withCount('group_user')->get();
        
        return view('livewire.chats-index');
    }
}
