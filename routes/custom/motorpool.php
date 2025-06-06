<?php

use App\Http\Livewire\Motorpool\Requests\FuelRequestIndex;
use App\Http\Livewire\Motorpool\Requests\FuelRequisition;
use App\Http\Livewire\Motorpool\Requests\FuelRequisitionSlip;
use App\Http\Livewire\Motorpool\Requests\RequestIndex;
use App\Http\Livewire\Motorpool\Requests\RequestNewSchedule;
use App\Http\Livewire\Motorpool\Requests\RequestShow;
use App\Http\Livewire\Motorpool\Schedule\ViewSchedules;
use App\Http\Livewire\Motorpool\Vehicle\VehicleCreate;
use App\Http\Livewire\Motorpool\Vehicle\VehicleEdit;
use App\Http\Livewire\Motorpool\Vehicle\VehicleIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('motorpool')->name('motorpool.')->group(function () {
    // Route::get('/vehicle', VehicleIndex::class)->name('vehicle.index');
    // Route::get('/vehicle/create/{from_schedules}', VehicleCreate::class)->name('vehicle.create');
    // Route::get('/vehicle/edit/{vehicle}', VehicleEdit::class)->name('vehicle.edit');
    Route::get('/schedule/view/{year?}/{month?}/{vehicle?}', ViewSchedules::class)->name('view-schedule');
    Route::get('/requests', RequestIndex::class)->name('request.index');
    Route::get('/requests/new', RequestNewSchedule::class)->name('request.new');
    Route::get('/requests/show/{request}', RequestShow::class)->name('request.show');
    Route::get('/requests/fuel-requisition', FuelRequestIndex::class)->name('request.fuel-requisition');
    Route::get('/requests/fuel-request/{request}', FuelRequisition::class)->name('request.fuel-request');
    Route::get('/requests/fuel-request-slip/{request}', FuelRequisitionSlip::class)->name('request.fuel-request-slip');
});
