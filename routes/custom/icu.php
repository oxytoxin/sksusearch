<?php

use App\Http\Livewire\ICU\IcuDashboard;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('icu')->name('icu.')->group(function () {
    Route::get('/dashboard', IcuDashboard::class)->name('dashboard');
});
