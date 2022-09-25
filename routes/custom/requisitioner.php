<?php

use App\Http\Livewire\Requisitioner\DisbursementVouchers\DisbursementVouchersCreate;
use App\Http\Livewire\Requisitioner\Itenerary\IteneraryCreate;
use App\Http\Livewire\Requisitioner\Itenerary\IteneraryShow;
use App\Http\Livewire\Requisitioner\TransactionsIndex;
use App\Http\Livewire\Requisitioner\TravelOrders\TravelOrdersCreate;
use App\Http\Livewire\Requisitioner\TravelOrders\TravelOrdersIndex;
use App\Http\Livewire\Requisitioner\TravelOrders\TravelOrdersShow;
use App\Http\Livewire\Requisitioner\TravelOrders\TravelOrdersView;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('requisitioner')->name('requisitioner.')->group(function () {
    Route::get('my-dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/transactions', TransactionsIndex::class)->name('transactions.index');
    Route::get('/travel-orders/create', TravelOrdersCreate::class)->name('travel-orders.create');
    Route::get('/travel-orders', TravelOrdersIndex::class)->name('travel-orders.index');
    Route::get('/travel-orders/{travel_order}', TravelOrdersShow::class)->name('travel-orders.show');
    Route::get('/travel-orders/view/{travel_order}', TravelOrdersView::class)->name('travel-orders.view'); 
    Route::get('/itenerary/create', IteneraryCreate::class)->name('itenerary.create');
    Route::get('/itenerary/{itenerary}', IteneraryShow::class)->name('itenerary.show');
    Route::get('/disbursement-vouchers/{voucher_subtype}/create', DisbursementVouchersCreate::class)->name('disbursement-vouchers.create');
});
