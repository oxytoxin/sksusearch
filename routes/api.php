<?php

use App\Http\Controllers\Api\SmsTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| SMS Testing API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('sms')->group(function () {
    // Send SMS (queued)
    Route::post('/send', [SmsTestController::class, 'send']);

    // Send SMS directly (bypass queue, for testing)
    Route::post('/test-direct', [SmsTestController::class, 'testDirect']);

    // Get SMS log by ID
    Route::get('/log/{id}', [SmsTestController::class, 'getLog']);

    // Get recent SMS logs
    Route::get('/logs', [SmsTestController::class, 'getLogs']);

    // Get SMS statistics
    Route::get('/stats', [SmsTestController::class, 'getStats']);

    // Get current provider info
    Route::get('/provider', [SmsTestController::class, 'getProvider']);

    // Test phone number formatting
    Route::post('/format-phone', [SmsTestController::class, 'formatPhone']);
});
