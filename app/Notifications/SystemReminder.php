<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SystemReminder extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $type;
    public $title;
    public $message;
    public $senderName;
    public $receiverName;
    public $senderId;
    public $receiver;
    public $disbursement_voucher;

    public function __construct(
        $type = null,
        $title = null,
        $message = null,
        $senderName = null,
        $receiverName = null,
        $senderId = null,
        $receiver = null,
        $disbursement_voucher = null
        )

    {

        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->senderName = $senderName;
        $this->receiverName = $receiverName;
        $this->receiverName = $receiverName;
        $this->senderId = $senderId;
        $this->receiver = $receiver;
        $this->disbursement_voucher = $disbursement_voucher;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'senderName' => $this->senderName,
            'receiverName' => $this->receiverName,
            'senderId' => $this->senderId,
            'receiverId' => $this->receiver->id,
            'profile_image' => null,
            'url' => route('disbursement-vouchers.show', $this->disbursement_voucher->id),
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
        return new PrivateChannel('notifications.' . $this->receiver->id);
    }
}
