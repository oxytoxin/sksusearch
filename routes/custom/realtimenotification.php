<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Icu\IcuDashboard;
use App\Http\Livewire\Test\CountetTest;
use App\Http\Livewire\Cashier\CashierDashboard;
use App\Http\Livewire\Accounting\AccountingDashboard;
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


});


