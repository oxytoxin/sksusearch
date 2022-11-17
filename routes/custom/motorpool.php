<?php

use App\Http\Livewire\Motorpool\Requests\RequestIndex;
use App\Http\Livewire\Motorpool\Requests\RequestNewSchedule;
use App\Http\Livewire\Motorpool\Schedule\ViewWeeklySchedule;
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
    Route::get('/schedule/weekly', ViewWeeklySchedule::class)->name('weekly-schedule');
    Route::get('/requests', RequestIndex::class)->name('request.index');
    Route::get('/requests/new', RequestNewSchedule::class)->name('request.new');
});
