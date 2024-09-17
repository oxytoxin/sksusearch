<?php

use App\Http\Livewire\WFP\AllocateFunds;
use App\Http\Livewire\WFP\EditAllocateFunds;
use App\Http\Livewire\WFP\AssignPersonnel;
use App\Http\Livewire\WFP\CreateWFP;
use App\Http\Livewire\WFP\SelectWfpType;
use App\Http\Livewire\WFP\FundAllocation;
use App\Http\Livewire\WFP\GeneratePpmp;
use App\Http\Livewire\WFP\WFPHistory;
use App\Http\Livewire\WFP\WfpReport;
use App\Http\Livewire\WFP\WfpSubmissions;
use App\Http\Livewire\WFP\WFPTypes;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('wfp')->name('wfp.')->group(function () {
    Route::get('/wfp-types', WFPTypes::class)->name('wfp-types');
    Route::get('/fund-allocation', FundAllocation::class)->name('fund-allocation');
    Route::get('/assign-personnel', AssignPersonnel::class)->name('assign-personnel');
    Route::get('/create-wfp/{record}', CreateWFP::class)->name('create-wfp');
    Route::get('/select-wfp', SelectWfpType::class)->name('select-wfp');
    Route::get('/wfp-history', WFPHistory::class)->name('wfp-history');
    Route::get('/allocate-funds/{record}', AllocateFunds::class)->name('allocate-funds');
    Route::get('/edit-allocate-funds/{record}', EditAllocateFunds::class)->name('edit-allocate-funds');
    Route::get('/print-wfp/{record}', WfpReport::class)->name('print-wfp');
    Route::get('/wfp-submissions', WfpSubmissions::class)->name('wfp-submissions');
    Route::get('/wfp-ppmp', GeneratePpmp::class)->name('generate-ppmp');
});
