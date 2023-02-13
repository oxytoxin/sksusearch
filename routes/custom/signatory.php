<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Requisitioner\Itinerary\ItineraryShow;
use App\Http\Livewire\Requisitioner\Itinerary\ItineraryPrint;
use App\Http\Livewire\Signatory\TravelOrders\TravelOrdersIndex;
use App\Http\Livewire\LiquidationReports\LiquidationReportsShow;
use App\Http\Livewire\Requisitioner\TravelOrders\TravelOrdersShow;
use App\Http\Livewire\Signatory\TravelOrders\TravelOrdersToSignView;
use App\Http\Livewire\Signatory\LiquidationReports\LiquidationReportsIndex;
use App\Http\Livewire\Signatory\DisbursementVouchers\DisbursementVouchersIndex;
use App\Http\Livewire\Signatory\Motorpool\RequestVehicleForSignature;
use App\Http\Livewire\Signatory\Motorpool\RequestVehicleSigned;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('signatory')->name('signatory.')->group(function () {
    Route::get('my-dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/travel-orders', TravelOrdersIndex::class)->name('travel-orders.index');
    Route::get('/travel-orders/{travel_order}', TravelOrdersShow::class)->name('travel-orders.show');
    Route::get('/travel-orders/view/{travel_order}', TravelOrdersToSignView::class)->name('travel-orders.view');
    Route::get('/itinerary/{itinerary}', ItineraryShow::class)->name('itinerary.show');
    Route::get('/itinerary/print/{itinerary}', ItineraryPrint::class)->name('itinerary.print');
    Route::get('/disbursement-vouchers', DisbursementVouchersIndex::class)->name('disbursement-vouchers.index');
    Route::get('/liquidation-reports', LiquidationReportsIndex::class)->name('liquidation-reports.index');
    Route::get('/liquidation-reports/{liquidation_report}', LiquidationReportsShow::class)->name('liquidation-reports.show');
    Route::get('/motorpool/request-vehicle/for-signature', RequestVehicleForSignature::class)->name('motorpool.for-signature');
    Route::get('/motorpool/request-vehicle/signed', RequestVehicleSigned::class)->name('motorpool.signed');
});
