<?php

    use App\Http\Livewire\Accounting\AccountingDashboard;
    use App\Http\Livewire\Cashier\CashierDashboard;
    use App\Http\Livewire\Cashier\Reports\AdviceOfChecksIssuedAndCancelled;
    use App\Http\Livewire\Cashier\Reports\LddapAdaSummaryReport;
    use App\Http\Livewire\Cashier\Reports\ReportOfAdviceToDebitAccountIssued;
    use App\Http\Livewire\Cashier\Reports\ReportOfChecksIssued;
    use App\Http\Livewire\Cashier\Reports\SliieReport;
    use App\Http\Livewire\Icu\IcuDashboard;
    use App\Http\Livewire\Signatory\TravelOrders\TravelOrdersIndex;
    use Illuminate\Support\Facades\Route;

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
    ])->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/dashboard', CashierDashboard::class)->name('dashboard');
    });
    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
    ])->prefix('cashier/reports')->name('cashier.reports.')->group(function () {
        Route::get('/rci', ReportOfChecksIssued::class)->name('rci');
        Route::get('/radai', ReportOfAdviceToDebitAccountIssued::class)->name('radai');
        Route::get('/acic', AdviceOfChecksIssuedAndCancelled::class)->name('acic');
        Route::get('/lddap-ada', LddapAdaSummaryReport::class)->name('lddap-ada');
        Route::get('/sliee', SliieReport::class)->name('sliee');
    });
