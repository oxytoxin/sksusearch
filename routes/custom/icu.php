<?php

use App\Http\Livewire\ICU\IcuDashboard;
use App\Http\Livewire\ICU\IcuManageVerifiedDocuments;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('icu')->name('icu.')->group(function () {
    Route::get('/dashboard', IcuDashboard::class)->name('dashboard');
    Route::get('/verified-documents/{disbursement_voucher}', IcuManageVerifiedDocuments::class)->name('verified_documents');
});
