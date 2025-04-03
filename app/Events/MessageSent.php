<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithBroadcasting;

    public $message;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastOn()
    {
        return new Channel('messages.' . $this->message->disbursement_voucher_id);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message->content,
            'sender' => $this->message->user->name,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
