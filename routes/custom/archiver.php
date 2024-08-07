<?php

use App\Http\Livewire\Accounting\AccountingDashboard;
use App\Http\Livewire\Archiver\ArchiveDocumentsCreate;
use App\Http\Livewire\Archiver\ArchiveDocumentsEdit;
use App\Http\Livewire\Archiver\ArchiveLegacyDocumentsCreate;
use App\Http\Livewire\Archiver\ArchiveLegacyDocumentsEdit;
use App\Http\Livewire\Archiver\ArchiverDashboard;
use App\Http\Livewire\Archiver\ArchiveStaleCheques;
use App\Http\Livewire\Archiver\ViewArchives;
use App\Http\Livewire\Archiver\ViewChqScannedDocuments;
use App\Http\Livewire\Archiver\ViewLegacyDocuments;
use App\Http\Livewire\Archiver\ViewLgcScannedDocuments;
use App\Http\Livewire\Archiver\ViewScannedDocuments;
use App\Http\Livewire\Archiver\ViewStaleCheques;
use App\Http\Livewire\Archiver\ViewRecordCounts;
use App\Http\Livewire\Cashier\CashierDashboard;
use App\Http\Livewire\Icu\IcuDashboard;
use App\Http\Livewire\Signatory\TravelOrders\TravelOrdersIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->prefix('archiver')->name('archiver.')->group(function () {
    Route::get('/dashboard', ArchiverDashboard::class)->name('dashboard');
    Route::get('/view-archives', ViewArchives::class)->name('view-archives');
    Route::get('/archive-document/update', ArchiveDocumentsEdit::class)->name('archive-doc.update');
    Route::get('/archive-document/new', ArchiveDocumentsCreate::class)->name('archive-doc.create');
    Route::get('/archive-document/legacy/update/{legacy_document}', ArchiveLegacyDocumentsEdit::class)->name('archive-leg-doc.update');
    Route::get('/archive-document/legacy/new', ArchiveLegacyDocumentsCreate::class)->name('archive-leg-doc.create');
    Route::get('/archive-cheque/new', ArchiveStaleCheques::class)->name('archive-cheques.create');
    Route::get('/view-scanned-documents/legacy/{legacy_document}-{edit}', ViewLgcScannedDocuments::class)->name('view-scanned-docs-lgc');
    Route::get('/view-scanned-documents/chueque/{archived_cheque}-{edit}', ViewChqScannedDocuments::class)->name('view-scanned-docs-chq');
    Route::get('/view-scanned-documents/{disbursement_voucher}', ViewScannedDocuments::class)->name('view-scanned-docs');
    Route::get('/view-legacy-documents/email/{document_code}', ViewLegacyDocuments::class)->name('view-legacy-docs-from-email');
    Route::get('/view-legacy-documents-count', ViewRecordCounts::class)->name('legacy-docs-count');
});
