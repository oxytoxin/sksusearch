<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EmployeeInformation;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TestNotification;
use App\Notifications\CashAdvanceCreation;
use App\Notifications\SubmissionRequestNotification;

class NotificationController extends Controller
{

    public function testNotification()
    {
        // $user = User::find(401);
        // $receiver = EmployeeInformation::accountantUser();
        // $user->notify(new SubmissionRequestNotification($receiver));

        $user = Auth::user(); // Get the logged-in user
        if ($user) {
            $user->notify(new TestNotification()); // Send test notification
            return 'Test notification sent!';
        }
        return 'No user logged in!';
    }


    public static function cashAdvanceCreation($user, $receiver, $disbursement_voucher){

        $receiver->notify( new CashAdvanceCreation($user, $receiver, $disbursement_voucher));
    }
    
}
