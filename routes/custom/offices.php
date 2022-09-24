<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('office')->name('office.')->group(function () {
    Route::get('office-dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
