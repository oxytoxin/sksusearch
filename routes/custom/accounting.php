<?php

use App\Http\Livewire\Accounting\AccountingDashboard;
use App\Http\Livewire\Icu\IcuDashboard;
use App\Http\Livewire\Signatory\TravelOrders\TravelOrdersIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('accounting')->name('accounting.')->group(function () {
    Route::get('/dashboard', AccountingDashboard::class)->name('dashboard');
});
