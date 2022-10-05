<?php

use App\Http\Livewire\Oic\OicAssign;
use App\Http\Livewire\Oic\OicDashboard;
use App\Http\Livewire\Oic\OicDesignations;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('oic')->name('oic.')->group(function () {
    Route::get('dashboard', OicDashboard::class)->name('dashboard');
    Route::get('assign', OicAssign::class)->name('assign');
    Route::get('designations', OicDesignations::class)->name('designations');
});
