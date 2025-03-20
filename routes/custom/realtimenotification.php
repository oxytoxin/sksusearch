<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Icu\IcuDashboard;
use App\Http\Livewire\Test\CountetTest;
use App\Http\Livewire\Reports\ShowCauseOrder;
use App\Http\Livewire\Cashier\CashierDashboard;
use App\Http\Livewire\Reports\EndorsementForFD;
use App\Http\Controllers\NotificationController;
use App\Http\Livewire\Accounting\AccountingDashboard;
use App\Http\Livewire\Reports\FormalManagementDemand;
use App\Http\Livewire\Reports\FormalManagementReminder;
use App\Http\Livewire\Signatory\TravelOrders\TravelOrdersIndex;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('notification')->name('notification.')->group(function () {


    Route::get('/fire-event',function () {
        event(new  \App\Events\TestEvent('Hello World'));
       return 'done';
    })->name('fire');

    Route::get('/test-page',  CountetTest::class)->name('test');


    Route::get('/test', [NotificationController::class,'testNotification'])->name('test-notification');

});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('print')->name('print.')->group(function () {



Route::get('formal-management-demand', FormalManagementDemand::class)->name('formal-management-demand');
Route::get('formal-management-reminder', FormalManagementReminder::class)->name('formal-management-reminder');
Route::get('endorsement-for-fd', EndorsementForFD::class)->name('endorsement-for-fd');
Route::get('show-cause-order', ShowCauseOrder::class)->name('show-cause-order');

});





