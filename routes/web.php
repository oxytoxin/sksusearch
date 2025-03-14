<?php

use App\Http\Controllers\HomeController;
use App\Http\Livewire\Shared\TravelCompletedCertificatePrint;
use App\Http\Livewire\Test\CountetTest;
use App\Http\Livewire\TestComponent;
use App\Models\FuelRequisition;
use App\Models\LegacyDocument;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/requisitioner/dashboard');
    Route::get('/disbursement-voucher-view/{disbursement_voucher}', [HomeController::class, 'disbursement_voucher_view'])->name('disbursement-vouchers.show');
    Route::get('/certification-of-travel-completion/{ctc}', TravelCompletedCertificatePrint::class)->name('ctc.show');
});
Route::get('auth/google', 'App\Http\Controllers\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'App\Http\Controllers\GoogleController@handleGoogleCallback');
Route::middleware(['auth:sanctum', 'verified'])->get('redirects', 'App\Http\Controllers\HomeController@index')->name('redirect');

Route::get('/test', function () {
    $fuel = FuelRequisition::first();
    return view('components.motorpool.fuel-requisition-slip', [
        'fuel_request' => $fuel
    ]);
});



