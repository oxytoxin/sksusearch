<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CashAdvanceSendFmr extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $user;
    public $receiver;
    public $disbursement_voucher;
    public function __construct($user, $receiver,$disbursement_voucher)


    {
        // dd($disbursement_voucher);
        $this->user = $user;
        $this->receiver = $receiver;
        $this->disbursement_voucher = $disbursement_voucher;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // ✅ Store in database + Real-time update
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'cash_advance_reminder_fmr',
            'title' => 'FMR Sent by'. $this->user->name,
            'message' => 'You have not liquidated your cash advance.',
            'profile_image' => $this->user->profile_photo_url,
            'url' => route('requisitioner.disbursement-vouchers.unliquidated'),
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
    return new Channel('notifications.' . $this->receiver->id);
}

}
