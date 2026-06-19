<?php

use App\Http\Livewire\Offices\OfficeDashboard;
use App\Http\Livewire\Offices\BatchTransmittalIndex;
use App\Http\Livewire\Offices\BatchTransmittalCreate;
use App\Http\Livewire\Offices\BatchTransmittalShow;
use App\Http\Livewire\Offices\BatchTransmittalPrint;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'ensure.signature',
])->prefix('office')->name('office.')->group(function () {
    Route::get('dashboard', OfficeDashboard::class)->name('dashboard');
    Route::get('batch-transmittal', BatchTransmittalIndex::class)->name('batch-transmittal.index');
    Route::get('batch-transmittal/create', BatchTransmittalCreate::class)->name('batch-transmittal.create');
    Route::get('batch-transmittal/{batch}', BatchTransmittalShow::class)->name('batch-transmittal.show');
    Route::get('batch-transmittal/{batch}/print', BatchTransmittalPrint::class)->name('batch-transmittal.print');
});
