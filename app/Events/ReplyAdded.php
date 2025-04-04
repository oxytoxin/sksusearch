<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message; // assuming Message model is in App\Models namespace

class ReplyAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reply;
    public $disbursement_voucher_id;

    public function __construct(Message $reply, $disbursement_voucher_id)
    {
        $this->reply = $reply;
        $this->disbursement_voucher_id = $disbursement_voucher_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('messages.' . $this->disbursement_voucher_id);
    }

    public function broadcastWith()
    {
        return [
            'reply' => $this->reply->content,
            'sender' => $this->reply->user->name,
            'created_at' => $this->reply->created_at->toDateTimeString(),
        ];
    }
}
