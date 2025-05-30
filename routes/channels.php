<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('notifications.{userId}', function ($user, $userId) {
//     return (int) $user->id === (int) $userId; // Allow only the owner to listen
// });

// Broadcast::channel('messages.{disbursementVoucherId}', function ($user, $disbursementVoucherId) {
//     return true;
// });
