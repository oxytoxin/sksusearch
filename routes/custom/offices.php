<?php

use App\Http\Livewire\Offices\OfficeDashboard;
use App\Http\Livewire\Offices\OfficeDisbursementVouchersIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'ensure.signature',
])->prefix('office')->name('office.')->group(function () {
    Route::get('dashboard', OfficeDashboard::class)->name('dashboard');
});
