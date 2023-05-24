<?php

use App\Http\Livewire\PettyCashVouchers\AccountantPettyCashDashboard;
use App\Http\Livewire\PettyCashVouchers\PcvReportIndex;
use App\Http\Livewire\PettyCashVouchers\PettyCashFundRecordIndex;
use App\Http\Livewire\PettyCashVouchers\PettyCashFundReplenish;
use App\Http\Livewire\PettyCashVouchers\PettyCashVouchersCreate;
use App\Http\Livewire\PettyCashVouchers\PettyCashVouchersIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('pcv')->name('pcv.')->group(function () {
    Route::get('/petty-cash-vouchers', PettyCashVouchersIndex::class)->name('index');
    Route::get('/accountant-dashboard', AccountantPettyCashDashboard::class)->name('accountant.dashboard');
    Route::get('/create', PettyCashVouchersCreate::class)->name('create');
    Route::get('/rppcv-reports', PcvReportIndex::class)->name('rppcv');
    Route::get('/petty-cash-fund/record', PettyCashFundRecordIndex::class)->name('pcf.record');
    Route::get('/petty-cash-fund/replenish', PettyCashFundReplenish::class)->name('pcf.replenish');
});
