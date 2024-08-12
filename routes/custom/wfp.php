<?php

use App\Http\Livewire\WFP\AssignPersonnel;
use App\Http\Livewire\WFP\CreateWFP;
use App\Http\Livewire\WFP\FundAllocation;
use App\Http\Livewire\WFP\WFPHistory;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('wfp')->name('wfp.')->group(function () {
    Route::get('/fund-allocation', FundAllocation::class)->name('fund-allocation');
    Route::get('/assign-personnel', AssignPersonnel::class)->name('assign-personnel');
    Route::get('/create-wfp', CreateWFP::class)->name('create-wfp');
    Route::get('/wfp-history', WFPHistory::class)->name('wfp-history');
});
