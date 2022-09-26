<?php

use App\Http\Livewire\Signatory\TravelOrders\TravelOrdersIndex;
use App\Http\Livewire\Signatory\TravelOrders\TravelOrdersToSignView;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('signatory')->name('signatory.')->group(function () {
    Route::get('my-dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/travel-orders', TravelOrdersIndex::class)->name('travel-orders.index');
    Route::get('/travel-orders/view/{travel_order}', TravelOrdersToSignView::class)->name('travel-orders.view');
});
