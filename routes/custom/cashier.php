<?php

use App\Http\Livewire\Accounting\AccountingDashboard;
use App\Http\Livewire\Cashier\CashierDashboard;
use App\Http\Livewire\Icu\IcuDashboard;
use App\Http\Livewire\Signatory\TravelOrders\TravelOrdersIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/dashboard', CashierDashboard::class)->name('dashboard');
});
