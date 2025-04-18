<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Models\EmployeeInformation;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SystemReminder;
use App\Notifications\TestNotification;
use App\Notifications\CashAdvanceSendFmr;
use App\Notifications\CashAdvanceCreation;
use App\Notifications\CashAdvanceStep2Reminder;
use App\Notifications\CashAdvanceStep3Reminder;
use App\Notifications\CashAdvanceStep4Reminder;
use App\Notifications\CashAdvanceStep5Reminder;
use App\Notifications\SubmissionRequestNotification;

class NotificationController extends Controller
{

    public static function sendMessage($content, $senderId, $receiverId, $disbursementVoucherId)
    {
        // Create a new message
        $message = new Message();
        $message->content = $content;
        $message->user_id = $senderId;
        $message->receiver_id = $receiverId;
        $message->disbursement_voucher_id = $disbursementVoucherId;
        $message->save();

        // Dispatch the MessageSent event
        event(new MessageSent($message));
    }

    // Other existing methods...



    public static function sendCASystemReminder($type, $title,$message,$senderName,$receiverName,$senderId,$receiver,$route,$disbursement_voucher
    ){

        $receiver->notify( new SystemReminder($type, $title, $message,$senderName,$receiverName,$senderId,$receiver,$route, $disbursement_voucher));
    }
    public static function cashAdvanceCreation($user, $receiver, $disbursement_voucher){

        $receiver->notify( new CashAdvanceCreation($user, $receiver, $disbursement_voucher));
    }
    public static function sendFMR($user, $receiver, $disbursement_voucher){

        $receiver->notify( new CashAdvanceSendFmr($user, $receiver, $disbursement_voucher));
    }

    public static function sendStep2Reminder($user, $receiver, $disbursement_voucher)
    {
        $receiver->notify(new CashAdvanceStep2Reminder($user, $receiver, $disbursement_voucher));
    }

    public static function sendStep3Reminder($user, $receiver, $disbursement_voucher)
    {
        $receiver->notify(new CashAdvanceStep3Reminder($user, $receiver, $disbursement_voucher));
    }

    public static function sendStep4Reminder($user, $receiver, $disbursement_voucher)
    {
        $receiver->notify(new CashAdvanceStep4Reminder($user, $receiver, $disbursement_voucher));
    }

    public static function sendStep5Reminder($user, $receiver, $disbursement_voucher)
    {
        $receiver->notify(new CashAdvanceStep5Reminder($user, $receiver, $disbursement_voucher));
    }


    //

//     public function sendStep2Reminder($record)
// {
//     $user = Auth::user();
//     $receiver = $record->user;

//     // Update liquidation_period_end_date (add 30 days)
//     $record->update([
//         'liquidation_period_end_date' => now()->addDays(30),
//         'is_sent' => true, // Mark reminder as sent
//     ]);

//     // Trigger Notification
//     NotificationController::sendStep2Reminder($user, $receiver, $record);

//     session()->flash('message', 'Step 2 Reminder Sent!');
// }


}
