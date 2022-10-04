<?php

use App\Http\Livewire\Accounting\AccountingDashboard;
use App\Http\Livewire\Archiver\ArchiverDashboard;
use App\Http\Livewire\Archiver\ViewArchives;
use App\Http\Livewire\Cashier\CashierDashboard;
use App\Http\Livewire\Icu\IcuDashboard;
use App\Http\Livewire\Signatory\TravelOrders\TravelOrdersIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('archiver')->name('archiver.')->group(function () {
    Route::get('/dashboard', ArchiverDashboard::class)->name('dashboard');
    Route::get('/view-archives', ViewArchives::class)->name('view-archives');
});
