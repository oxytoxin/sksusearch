<?php

use App\Http\Livewire\Requisitioner\TransactionsIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('requisitioner')->name('requisitioner.')->group(function () {
    Route::get('my-dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/transactions', TransactionsIndex::class)->name('transactions.index');
});
