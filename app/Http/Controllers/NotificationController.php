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



    public static function cashAdvanceCreation($user, $receiver, $disbursement_voucher){

        $receiver->notify( new CashAdvanceCreation($user, $receiver, $disbursement_voucher));
    }



}
