<?php

use App\Http\Livewire\Motorpool\Vehicle\VehicleCreate;
use App\Http\Livewire\Motorpool\Vehicle\VehicleEdit;
use App\Http\Livewire\Motorpool\Vehicle\VehicleIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('motorpool')->name('motorpool.')->group(function () {
    Route::get('/vehicle', VehicleIndex::class)->name('vehicle.index');
    Route::get('/vehicle/create', VehicleCreate::class)->name('vehicle.create');
    Route::get('/vehicle/edit/{vehicle}', VehicleEdit::class)->name('vehicle.edit');
    Route::get('/vehicle/edit/{vehicle}', VehicleEdit::class)->name('vehicle.edit');
});
