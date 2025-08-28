<?php

use App\Http\Livewire\WFP\AccountingRequestedSupplies;
use App\Http\Livewire\WFP\AddSupplementalFund;
use App\Http\Livewire\WFP\AllocateFunds;
use App\Http\Livewire\WFP\EditAllocateFunds;
use App\Http\Livewire\WFP\AssignPersonnel;
use App\Http\Livewire\WFP\CreateWFP;
use App\Http\Livewire\WFP\SelectWfpType;
use App\Http\Livewire\WFP\FundAllocation;
use App\Http\Livewire\WFP\GeneratePpmp;
use App\Http\Livewire\WFP\GenerateWfpPpmp;
use App\Http\Livewire\WFP\PriceListDocument;
use App\Http\Livewire\WFP\ReportSupply;
use App\Http\Livewire\WFP\RequestSupply;
use App\Http\Livewire\WFP\RequestSupplyEdit;
use App\Http\Livewire\WFP\SupplyReportList;
use App\Http\Livewire\WFP\SupplyRequestedSupplies;
use App\Http\Livewire\WFP\SupplyRequestList;
use App\Http\Livewire\WFP\UserPRE;
use App\Http\Livewire\WFP\ViewReportedSupply;
use App\Http\Livewire\WFP\ViewReportSupplyDetails;
use App\Http\Livewire\WFP\ViewSupplyRequest;
use App\Http\Livewire\WFP\WFPHistory;
use App\Http\Livewire\WFP\WfpPpmp;
use App\Http\Livewire\WFP\WfpReport;
use App\Http\Livewire\WFP\WfpSubmissions;
use App\Http\Livewire\WFP\WFPTypes;
use App\Http\Livewire\WFP\DeactivatedPricelists;
use App\Http\Livewire\WFP\DevPage;
use App\Http\Livewire\WFP\EditSupplementalFundQ1;
use App\Http\Livewire\WFP\ViewAllocatedFund;
use App\Http\Livewire\WFP\ViewRemarks;
use App\Http\Livewire\WFP\ViewSupplementalFunds;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('wfp')->name('wfp.')->group(function () {
    Route::get('/wfp-types', WFPTypes::class)->name('wfp-types');
    // Route::get('/fund-allocation/{filter?}', FundAllocation::class)->name('fund-allocation');
    Route::view('/fund-allocation/{filter?}', 'fund-allocations')->name('fund-allocation');

    Route::get('/assign-personnel', AssignPersonnel::class)->name('assign-personnel');
    Route::get('/create-wfp/{record}/{wfpType}/{isEdit}/{isSupplemental}', CreateWFP::class)->name('create-wfp');
    Route::view('/select-wfp', 'select-wfp-type')->name('select-wfp');
    Route::get('/wfp-history', WFPHistory::class)->name('wfp-history');
    Route::get('/allocate-funds/{record}', AllocateFunds::class)->name('allocate-funds');
    Route::get('/edit-allocate-funds/{record}/{wfpType}', EditAllocateFunds::class)->name('edit-allocate-funds');
    Route::get('/view-allocated-funds/{record}/{wfpType}', ViewAllocatedFund::class)->name('view-allocated-funds');
    Route::get('/print-wfp/{record}/{isSupplemental}', WfpReport::class)->name('print-wfp');
    // Route::get('/wfp-submissions/{filter?}', WfpSubmissions::class)->name('wfp-submissions');
    Route::view('/wfp-submissions/{filter?}', 'wfp-submissions')->name('wfp-submissions');

    Route::get('/wfp-pre', GeneratePpmp::class)->name('generate-ppmp');
    Route::get('/wfp-ppmp', GenerateWfpPpmp::class)->name('generate-wfp-ppmp');
    Route::get('/print-ppmp/{record}/{isSupplemental}', WfpPpmp::class)->name('print-ppmp');
    Route::get('/print-pre/{record}/{isSupplemental}', UserPRE::class)->name('print-pre');
    Route::get('/request-new-supply', RequestSupply::class)->name('request-supply');
    Route::get('/edit-supply-request/{record}', RequestSupplyEdit::class)->name('request-supply-edit');
    Route::get('/view-supply-request/{record}', ViewSupplyRequest::class)->name('request-supply-view');
    Route::get('/view-supply-report-details/{record}', ViewReportSupplyDetails::class)->name('report-supply-view-details');
    Route::get('/report-supply/{record?}', ReportSupply::class)->name('report-supply');
    Route::get('/request-supply-list', SupplyRequestList::class)->name('request-supply-list');
    Route::get('/report-supply-list', SupplyReportList::class)->name('report-supply-list');
    Route::get('/reported-supply-list', ViewReportedSupply::class)->name('reported-supply-list');
    Route::get('/supply-requested-supplies', SupplyRequestedSupplies::class)->name('supply-requested-suppluies');
    Route::get('/accounting-requested-supplies', AccountingRequestedSupplies::class)->name('accounting-requested-suppluies');
    Route::get('/pricelist-document', PriceListDocument::class)->name('pricelist-document');
    Route::get('/deactivated-pricelists', DeactivatedPricelists::class)->name('deactivated-pricelists');
    Route::get('/view-remarks/{record}', ViewRemarks::class)->name('view-remarks');
    Route::get('/add-supplemental-fund/{record}/{wfpType}/{isForwarded}', AddSupplementalFund::class)->name('add-supplemental-fund');
    Route::get('/view-supplemental-fund/{record}/{wfpType}/{isForwarded}', ViewSupplementalFunds::class)->name('view-supplemental-fund');
    Route::get('/edit-supplemental-funds-q1/{record}/{wfpType}/{isForwarded}', EditSupplementalFundQ1::class)->name('edit-supplemental-funds-q1');
    Route::get('/dev-page', DevPage::class)->name('dev-page');
});
