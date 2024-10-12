<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// callback and error handlers, can't using webhook for myfatoorah, needed account
Route::get('callback', [OrderController::class, 'callback'])->name('order.callback');
Route::get('error', [OrderController::class, 'error'])->name('order.error');

// Webhook for stripe
Route::post('/webhook', [OrderController::class, 'handleWebhook']);

Route::middleware(['throttle:api'])->group(function() {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:api', 'role:admin'])->group(function () {
        Route::group(['prefix' => 'orders'], function () {
            Route::post('/', [OrderController::class, 'store']);
            Route::get('/', [OrderController::class, 'index']);
            Route::put('place_order/{id}', [OrderController::class, 'placeOrder']);
            Route::put('{id}', [OrderController::class, 'update']);
        });
    });
});
