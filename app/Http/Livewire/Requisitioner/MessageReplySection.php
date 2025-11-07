<?php

namespace App\Http\Livewire\Requisitioner;

use App\Events\MessageDeleted;
use App\Events\MessageSent;
use App\Events\ReplyAdded;
use App\Http\Controllers\NotificationController;
use App\Models\EmployeeInformation;
use App\Models\Message;
use Livewire\Component;
use WireUi\Traits\Actions;

class MessageReplySection extends Component
{
    use Actions;

    public $disbursement_voucher;

    public $messageContent;

    public $messages;

    public $replyingTo = null;

    public $replyContent = '';

    protected $listeners = ['messageAdded', 'replyAdded', 'messageDeleted', 'refreshMessages'];

    public $currentRouteName;

    public function mount($disbursement_voucher)
    {

        $this->disbursement_voucher = $disbursement_voucher;
        $this->loadMessages();
        $this->currentRouteName = request()->route()->getName();

    }

    public function render()
    {
        return view('livewire.requisitioner.message-reply-section');
    }

    public function addMessage()
    {
        $this->validate([
            'messageContent' => 'required|string|min:1',
        ], [
            'messageContent.required' => 'The message content is required.',
        ]);
        $message = new Message;
        $message->content = $this->messageContent;
        $message->user_id = auth()->id();
        $message->messageable_type = 'App\\Models\\DisbursementVoucher';
        $message->messageable_id = $this->disbursement_voucher->id;

        if (auth()->id() === $this->disbursement_voucher->user_id) {
            $message->receiver_id = EmployeeInformation::accountantUser()->id;
        } else {
            $message->receiver_id = $this->disbursement_voucher->user_id;
        }

        $message->save();

        $this->emit('messageAdded', $message->id);
        event(new MessageSent($message, $this->disbursement_voucher->id));

        $this->messageContent = '';

        if ($this->currentRouteName === 'print.formal-management-reminder') {
            $voucherOwner = auth()->user(); // the one sending the message
            $accountingOfficer = $this->disbursement_voucher->user; // the DV owner / requisitioner
            $president = EmployeeInformation::presidentUser();

           NotificationController::sendCASystemReminder(
    type: 'Message',
    title: 'New Message in Formal Management Reminder',
    message: 'You received a new message from '.$sender->name.
             ' regarding Cash Advance DV-'.$this->disbursement_voucher->dv_number.'.',
    senderName: $sender->name,
    receiverName: $receiver->name,
    senderId: $sender->id,
    receiver: $receiver,
    route: route('print.formal-management-reminder', $this->disbursement_voucher->id),
    disbursement_voucher: $this->disbursement_voucher
);

        }

    }

    public function addReply($parentId)
    {
        $this->validate([
            'replyContent' => 'required|string|min:1',
        ], [
            'replyContent.required' => 'The reply content is required.',
        ]);

        $reply = new Message;
        $reply->content = $this->replyContent;
        $reply->user_id = auth()->id();
        $reply->parent_id = $parentId;
        $reply->messageable_type = 'App\\Models\\DisbursementVoucher';
        $reply->messageable_id = $this->disbursement_voucher->id;

        if (auth()->id() === $this->disbursement_voucher->user_id) {
            $reply->receiver_id = EmployeeInformation::accountantUser()->id;
        } else {
            $reply->receiver_id = $this->disbursement_voucher->user_id;
        }

        $reply->save();

        $this->replyContent = '';
        $this->replyingTo = null;
        $this->emit('replyAdded', $reply->id);
        $this->emit('refreshMessages');
        event(new ReplyAdded($reply, $this->disbursement_voucher->id));
    }

    public function confirmDelete($messageId)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Do you really want to delete this message?',
            'acceptLabel' => 'Yes, Delete it',
            'method' => 'deleteMessage',
            'params' => $messageId,
        ]);
    }

    public function deleteMessage($messageId)
    {
        $message = Message::find($messageId);
        if ($message) {
            $message->delete();
            $this->emit('messageDeleted', $messageId);
            $this->emit('refreshMessages');
            event(new MessageDeleted($messageId, $this->disbursement_voucher->id));
        }
    }

    public function loadMessages()
    {
        $this->messages = Message::where('messageable_type', 'App\\Models\\DisbursementVoucher')
            ->where('messageable_id', $this->disbursement_voucher->id)
            ->whereNull('parent_id')
            ->with('replies')
            ->orderBy('created_at', 'desc')
            ->get();

    }

    public function messageAdded($messageId)
    {
        $this->loadMessages();
    }

    public function replyAdded($replyId)
    {
        $this->loadMessages();
    }

    public function messageDeleted($messageId)
    {
        $this->loadMessages();
    }

    public function refreshMessages()
    {
        $this->loadMessages();
    }
}
