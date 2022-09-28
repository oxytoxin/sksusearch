<?php

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

Route::middleware('auth')->get('/', TestComponent::class);
Route::get('auth/google', 'App\Http\Controllers\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'App\Http\Controllers\GoogleController@handleGoogleCallback'); 
Route::middleware(['auth:sanctum', 'verified'])->get('redirects', 'App\Http\Controllers\HomeController@index')->name('redirect');
Route::view('/401-page', 'errs.401-page')->name('401-error');  