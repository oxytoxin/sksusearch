<?php

namespace App\Events;

use BeyondCode\LaravelWebSockets\WebSockets\Channels\Channel as ChannelsChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct(public $message)
    {
        //
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

     public function broadcastAs()
     {
         return 'TestEvent';
     }

     public function broadcastWith()
     {
         return ['message' => $this->message];
     }


    public function broadcastOn()
    {
        return new Channel('testchannel');
    }
}
