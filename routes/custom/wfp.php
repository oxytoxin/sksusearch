<?php

use App\Http\Livewire\WFP\AccountingRequestedSupplies;
use App\Http\Livewire\WFP\AllocateFunds;
use App\Http\Livewire\WFP\EditAllocateFunds;
use App\Http\Livewire\WFP\AssignPersonnel;
use App\Http\Livewire\WFP\CreateWFP;
use App\Http\Livewire\WFP\SelectWfpType;
use App\Http\Livewire\WFP\FundAllocation;
use App\Http\Livewire\WFP\GeneratePpmp;
use App\Http\Livewire\WFP\GenerateWfpPpmp;
use App\Http\Livewire\WFP\RequestSupply;
use App\Http\Livewire\WFP\RequestSupplyEdit;
use App\Http\Livewire\WFP\SupplyRequestedSupplies;
use App\Http\Livewire\WFP\SupplyRequestList;
use App\Http\Livewire\WFP\UserPRE;
use App\Http\Livewire\WFP\ViewSupplyRequest;
use App\Http\Livewire\WFP\WFPHistory;
use App\Http\Livewire\WFP\WfpPpmp;
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
    Route::get('/wfp-pre', GeneratePpmp::class)->name('generate-ppmp');
    Route::get('/wfp-ppmp', GenerateWfpPpmp::class)->name('generate-wfp-ppmp');
    Route::get('/print-ppmp/{record}', WfpPpmp::class)->name('print-ppmp');
    Route::get('/print-pre/{record}', UserPRE::class)->name('print-pre');
    Route::get('/request-new-supply', RequestSupply::class)->name('request-supply');
    Route::get('/edit-supply-request/{record}', RequestSupplyEdit::class)->name('request-supply-edit');
    Route::get('/view-supply-request/{record}', ViewSupplyRequest::class)->name('request-supply-view');
    Route::get('/request-supply-list', SupplyRequestList::class)->name('request-supply-list');
    Route::get('/supply-requested-supplies', SupplyRequestedSupplies::class)->name('supply-requested-suppluies');
    Route::get('/accounting-requested-supplies', AccountingRequestedSupplies::class)->name('accounting-requested-suppluies');
});
