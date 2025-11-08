<?php

namespace App\Http\Livewire\Requisitioner;

use App\Models\Message;
use Livewire\Component;
use App\Events\ReplyAdded;
use WireUi\Traits\Actions;
use App\Events\MessageSent;
use App\Events\MessageDeleted;
use App\Models\EmployeeInformation;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationController;

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

        $voucher = $this->disbursement_voucher;
        $sender = Auth::user();

        $message = new Message;
        $message->content = $this->messageContent;
        $message->user_id = $sender->id;
        $message->messageable_type = 'App\\Models\\DisbursementVoucher';
        $message->messageable_id = $voucher->id;

        $message->receiver_id = ($sender->id === $voucher->user_id)
            ? EmployeeInformation::accountantUser()->id
            : $voucher->user_id;

        $message->save();

        $this->emit('messageAdded', $message->id);
        event(new MessageSent($message, $this->disbursement_voucher->id));

        $this->messageContent = '';


        $current = $this->currentRouteName;

        $accountant = EmployeeInformation::accountantUser()?->user;
        $president = EmployeeInformation::presidentUser()?->user;
        $auditor = EmployeeInformation::auditorUser()?->user;
        $voucherOwner = $voucher->user;

        $receivers = [];

        switch ($current) {
            // FORMAL MANAGEMENT REMINDER
            case 'print.formal-management-reminder':
            case 'print.formal-management-demand':
                if ($sender->id === $voucherOwner->id && $accountant) {
                    $receivers[] = $accountant;
                } elseif ($accountant && $sender->id === $accountant->id) {
                    $receivers[] = $voucherOwner;
                }
                break;

            // SHOW CAUSE ORDER
            case 'print.show-cause-order':
                if ($sender->id === $voucherOwner->id && $president) {
                    $receivers[] = $president;
                } elseif ($president && $sender->id === $president->id) {
                    $receivers[] = $voucherOwner;
                }
                break;

            // ENDORSEMENT FOR FD
            case 'print.endorsement-for-fd':
                if ($sender->id === $president?->id && $auditor) {
                    $receivers[] = $auditor;
                } elseif ($auditor && $sender->id === $auditor->id) {
                    $receivers[] = $president;
                }
                break;

            // FORMAL DEMAND FILE (FD)
            case 'print.endorsement-for-fd-file':
                if ($sender->id === $voucherOwner->id && $auditor) {
                    $receivers[] = $auditor;
                } elseif ($auditor && $sender->id === $auditor->id) {
                    $receivers[] = $voucherOwner;
                }
                break;
        }

        // --------------------------------------------------
        // Send notifications only to active participants
        // --------------------------------------------------
        foreach ($receivers as $receiver) {
            if (!$receiver) continue;

            NotificationController::sendCASystemReminder(
                type: 'Message',
                title: 'New Message in ' . ucwords(str_replace('-', ' ', str_replace('print.', '', $current))),
                message: 'You received a new message from ' . $sender->name .
                    ' regarding Cash Advance DV-' . $voucher->dv_number . '.',
                senderName: $sender->name,
                receiverName: $receiver->name,
                senderId: $sender->id,
                receiver: $receiver,
                route: route($current, $voucher->id),
                disbursement_voucher: $voucher
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

    $voucher = $this->disbursement_voucher;
    $sender  = auth()->user();

    $reply = new Message;
    $reply->content = $this->replyContent;
    $reply->user_id = $sender->id;
    $reply->parent_id = $parentId;
    $reply->messageable_type = 'App\\Models\\DisbursementVoucher';
    $reply->messageable_id = $voucher->id;

    // Identify route and participants
    $current     = $this->currentRouteName;
    $accountant  = EmployeeInformation::accountantUser()?->user;
    $president   = EmployeeInformation::presidentUser()?->user;
    $auditor     = EmployeeInformation::auditorUser()?->user;
    $voucherOwner = $voucher->user;
    $receiver = null;

    switch ($current) {
        // FMR / FMD → Accounting ↔ User
        case 'print.formal-management-reminder':
        case 'print.formal-management-demand':
            $receiver = ($sender->id === $voucherOwner->id)
                ? $accountant
                : $voucherOwner;
            break;

        // SCO → President ↔ User
        case 'print.show-cause-order':
            $receiver = ($sender->id === $voucherOwner->id)
                ? $president
                : $voucherOwner;
            break;

        // Endorsement → President ↔ Auditor
        case 'print.endorsement-for-fd':
            $receiver = ($sender->id === $president?->id)
                ? $auditor
                : $president;
            break;

        // Formal Demand File → Auditor ↔ User
        case 'print.endorsement-for-fd-file':
            $receiver = ($sender->id === $voucherOwner->id)
                ? $auditor
                : $voucherOwner;
            break;
    }

    // Assign receiver ID safely
    $reply->receiver_id = $receiver?->id ?? null;
    $reply->save();

    // Reset and emit
    $this->replyContent = '';
    $this->replyingTo = null;
    $this->emit('replyAdded', $reply->id);
    $this->emit('refreshMessages');
    event(new ReplyAdded($reply, $voucher->id));

    // (Optional) Send notification
    if ($receiver) {
        NotificationController::sendCASystemReminder(
            type: 'Message',
            title: 'New Reply in ' . ucwords(str_replace('-', ' ', str_replace('print.', '', $current))),
            message: 'You received a new reply from ' . $sender->name .
                     ' regarding Cash Advance DV-' . $voucher->dv_number . '.',
            senderName: $sender->name,
            receiverName: $receiver->name,
            senderId: $sender->id,
            receiver: $receiver,
            route: route($current, $voucher->id),
            disbursement_voucher: $voucher
        );
    }
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
