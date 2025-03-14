<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CashAdvanceCreation extends Notification implements ShouldBroadcast
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
            'type' => 'cash_advance_creation',
            'title' => 'Cash Advance cheque number '. $this->disbursement_voucher->cheque_number??'No Number',
            'message' => 'Cash advance assigned cheque number by' . $this->user->name,
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
        return new PrivateChannel('notifications.' . $this->user->id);
    }



}
