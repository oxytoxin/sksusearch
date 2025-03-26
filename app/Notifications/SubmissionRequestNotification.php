<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SubmissionRequestNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // ✅ Store in database + Real-time update
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'submission_request',
            'title' => 'New Submission Request',
            'message' => 'A new submission request has been made: ' . $this->user->name,
            'url' => 'facebook.com',
            'time' => now(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'data' => $this->toDatabase($notifiable),
        ];
    }

    public function broadcastOn()
    {
        return new Channel('notifications.' . $this->user->id);
    }

}
