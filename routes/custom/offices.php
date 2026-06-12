<?php

use App\Http\Livewire\Offices\OfficeDashboard;
use App\Http\Livewire\Offices\OfficeDisbursementVouchersIndex;
use App\Http\Livewire\Offices\SentEmailsIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('office')->name('office.')->group(function () {
    Route::get('dashboard', OfficeDashboard::class)->name('dashboard');
    // Accounting-only "Sent Emails" log (access enforced in SentEmailsIndex::mount()).
    Route::get('sent-emails', SentEmailsIndex::class)->name('sent-emails');
});
