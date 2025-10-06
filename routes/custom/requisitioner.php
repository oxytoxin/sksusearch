<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Requisitioner\TutorialIndex;
use App\Http\Livewire\Requisitioner\PromptSignature;
use App\Http\Livewire\Requisitioner\TransactionsIndex;
use App\Http\Livewire\Requisitioner\PromptContactNumber;
use App\Http\Livewire\Requisitioner\Itinerary\ItineraryShow;

use App\Http\Livewire\Requisitioner\Itinerary\ItineraryPrint;
use App\Http\Livewire\Requisitioner\Itinerary\ItineraryCreate;
use App\Http\Livewire\LiquidationReports\LiquidationReportsShow;
use App\Http\Livewire\Requisitioner\Motorpool\RequestVehicleShow;
use App\Http\Livewire\Requisitioner\Motorpool\RequestVehicleIndex;
use App\Http\Livewire\Requisitioner\TravelOrders\TravelOrdersShow;
use App\Http\Livewire\Requisitioner\TravelOrders\TravelOrdersView;
use App\Http\Livewire\Requisitioner\DisbursementVouchers\MyNotices;
use App\Http\Livewire\Requisitioner\Motorpool\RequestVehicleCreate;
use App\Http\Livewire\Requisitioner\TravelOrders\TravelOrdersIndex;
use App\Http\Livewire\Requisitioner\TravelOrders\TravelOrdersCreate;
use App\Http\Livewire\Requisitioner\Motorpool\VehicleRequestFormShow;
use App\Http\Livewire\Requisitioner\DisbursementVouchers\CashAdvanceReminders;
use App\Http\Livewire\Requisitioner\LiquidationReports\LiquidationReportsIndex;
use App\Http\Livewire\Requisitioner\LiquidationReports\LiquidationReportsCreate;
use App\Http\Livewire\Requisitioner\DisbursementVouchers\DisbursementVouchersIndex;
use App\Http\Livewire\Requisitioner\LiquidationReports\LiquidationReportsCancelled;
use App\Http\Livewire\Requisitioner\DisbursementVouchers\DisbursementVouchersCreate;
use App\Http\Livewire\Requisitioner\DisbursementVouchers\DisbursementVouchersCancelled;
use App\Http\Livewire\Requisitioner\DisbursementVouchers\DisbursementVouchersUnliquidated;
use App\Http\Livewire\Requisitioner\DisbursementVouchers\LiquidationStatus;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('requisitioner')->name('requisitioner.')->group(function () {
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/require-contact-number', PromptContactNumber::class)->name('contact-number');
    Route::get('/require-signature', PromptSignature::class)->name('signature');
    Route::get('/transactions', TransactionsIndex::class)->name('transactions.index');
    Route::get('/tutorials', TutorialIndex::class)->name('tutorials.index');
    Route::get('/travel-orders/create', TravelOrdersCreate::class)->name('travel-orders.create');
    Route::get('/travel-orders', TravelOrdersIndex::class)->name('travel-orders.index');
    Route::get('/travel-orders/{travel_order}', TravelOrdersShow::class)->name('travel-orders.show');
    Route::get('/travel-orders/view/{travel_order}', TravelOrdersView::class)->name('travel-orders.view');
    Route::get('/itinerary/create', ItineraryCreate::class)->name('itinerary.create');
    Route::get('/itinerary/{itinerary}', ItineraryShow::class)->name('itinerary.show');
    Route::get('/itinerary/print/{itinerary}', ItineraryPrint::class)->name('itinerary.print');
    Route::get('/disbursement-vouchers', DisbursementVouchersIndex::class)->name('disbursement-vouchers.index');

    Route::get('/liquidation/status', LiquidationStatus::class)->name('disbursement-vouchers.liquidation.status');
    Route::get('/unliquidated-disbursement-vouchers', DisbursementVouchersUnliquidated::class)->name('disbursement-vouchers.unliquidated');
    Route::get('my-disbursement/notices/{DisbursementVoucher}', MyNotices::class)->name('disbursement-my-notices');
    Route::get('/cancelled-disbursement-vouchers', DisbursementVouchersCancelled::class)->name('disbursement-vouchers.cancelled');
    Route::get('/disbursement-vouchers/{voucher_subtype}/create', DisbursementVouchersCreate::class)->name('disbursement-vouchers.create');
    Route::get('/liquidation-reports/create', LiquidationReportsCreate::class)->name('liquidation-reports.create');
    Route::get('/cancelled-liquidation-reports', LiquidationReportsCancelled::class)->name('liquidation-reports.cancelled');
    Route::get('/liquidation-reports', LiquidationReportsIndex::class)->name('liquidation-reports.index');
    Route::get('/liquidation-reports/{liquidation_report}', LiquidationReportsShow::class)->name('liquidation-reports.show');
    Route::get('/motorpool/request-vehicle', RequestVehicleIndex::class)->name('motorpool.index');
    Route::get('/motorpool/request-vehicle/new', RequestVehicleCreate::class)->name('motorpool.create');
    Route::get('/motorpool/vehicle-request-form/{request}', VehicleRequestFormShow::class)->name('motorpool.show-request-form');
    Route::get('/motorpool/vehicle-request-details/{request}', RequestVehicleShow::class)->name('motorpool.show');
    Route::get('/ca-reminders', CashAdvanceReminders::class)->name('ca-reminders');
    // Route::get('/ca-reminders', CashAdvanceReminders::class)->name('ca-reminders');
});
