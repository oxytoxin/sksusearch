<?php

use App\Http\Livewire\Requisitioner\Itenerary\IteneraryCreate;
use App\Http\Livewire\Requisitioner\TransactionsIndex;
use App\Http\Livewire\Requisitioner\TravelOrders\TravelOrdersCreate;
use App\Http\Livewire\Requisitioner\TravelOrders\TravelOrdersShow;
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
    Route::get('/travel-orders/{travel_order}', TravelOrdersShow::class)->name('travel-orders.show');
    Route::get('/itenerary/create', IteneraryCreate::class)->name('itenerary.create');
});
