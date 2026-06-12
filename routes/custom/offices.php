<?php

use App\Http\Livewire\Offices\OfficeDashboard;
use App\Http\Livewire\Offices\OfficeDisbursementVouchersIndex;
use App\Http\Livewire\Offices\SentEmailsIndex;
use App\Http\Livewire\Offices\TransmittalsIndex;
use App\Models\Transmittal;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('office')->name('office.')->group(function () {
    Route::get('dashboard', OfficeDashboard::class)->name('dashboard');
    // Accounting-only "Sent Emails" log (access enforced in SentEmailsIndex::mount()).
    Route::get('sent-emails', SentEmailsIndex::class)->name('sent-emails');

    // Transmittals (item 3). Access enforced in TransmittalsIndex::mount().
    Route::get('transmittals', TransmittalsIndex::class)->name('transmittals');
    Route::get('transmittals/{transmittal}/print', function (Transmittal $transmittal) {
        return view('print.transmittal-form', [
            'transmittal' => $transmittal->load([
                'disbursement_vouchers.voucher_subtype',
                'disbursement_vouchers.disbursement_voucher_particulars',
                'prepared_by_user.employee_information',
            ]),
        ]);
    })->name('transmittals.print');
    Route::get('transmittals/{transmittal}/acknowledgment', function (Transmittal $transmittal) {
        return view('print.transmittal-acknowledgment', [
            'transmittal' => $transmittal->load([
                'disbursement_vouchers.voucher_subtype',
                'disbursement_vouchers.disbursement_voucher_particulars',
                'prepared_by_user.employee_information',
            ]),
        ]);
    })->name('transmittals.acknowledgment');
});
