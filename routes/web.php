<?php

use App\Http\Controllers\HomeController;
use App\Http\Livewire\TestComponent;
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
    Route::get('/', TestComponent::class);
    Route::get('/disbursement-voucher-view/{disbursement_voucher}', [HomeController::class, 'disbursement_voucher_view'])->name('disbursement-vouchers.show');
});
Route::get('auth/google', 'App\Http\Controllers\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'App\Http\Controllers\GoogleController@handleGoogleCallback');
Route::middleware(['auth:sanctum', 'verified'])->get('redirects', 'App\Http\Controllers\HomeController@index')->name('redirect');
Route::view('/401-page', 'error_pages.401-error')->name('401-error');
Route::view('/info', 'php-info')->name('php-info');
