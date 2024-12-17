<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WalletTypeController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



// protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('wallets', WalletController::class);
    Route::get('wallets/search', [WalletController::class, 'search']);
    Route::apiResource('wallet-types', WalletTypeController::class);
    Route::get('wallet-types/search', [WalletTypeController::class, 'search']);
    Route::apiResource('transactions', TransactionController::class);
    Route::get('transactions/search', [TransactionController::class, 'search']);
});

// user routes
Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', 'all_users');
        Route::get('/search', 'search');
        Route::get('/{id}', 'get_user');
        Route::put('/{id}', 'update_user');
        Route::delete('/{id}', 'delete_account');
        Route::any('/logout', 'logout');
        Route::put('/change-password', 'changePassword');
        Route::post('/reset-password', 'resetPassword');
        Route::post('/forgot-password', 'forgotPassword');
    });
});
