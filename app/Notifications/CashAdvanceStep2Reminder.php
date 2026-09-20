<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CashAdvanceStep2Reminder extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $user;
    public $receiver;
    public $disbursement_voucher;

    public function __construct($user, $receiver, $disbursement_voucher)
    {
        $this->user = $user;
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
            'type' => 'cash_advance_step_2',
            'title' => 'Step 2 Reminder Sent',
            'message' => 'Reminder for cash advance (Cheque No. ' . ($this->disbursement_voucher->cheque_number ?? 'No Number') . ') was sent by ' . $this->user->name,
            'sender' => $this->user->id,
            'profile_image' => $this->user->profile_photo_url,
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
