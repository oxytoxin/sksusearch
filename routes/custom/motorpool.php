<?php

use App\Http\Livewire\Motorpool\Vehicle\VehicleIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('motorpool')->name('motorpool.')->group(function () {
    Route::get('/vehicle', VehicleIndex::class)->name('vehicle.index');
});
