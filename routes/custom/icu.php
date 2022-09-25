<?php

use App\Http\Livewire\Icu\IcuDashboard;
use App\Http\Livewire\Signatory\TravelOrders\TravelOrdersIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('icu')->name('icu.')->group(function () {
    Route::get('/dashboard', IcuDashboard::class)->name('dashboard');
});
