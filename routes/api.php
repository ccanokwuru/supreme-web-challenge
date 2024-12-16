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
    Route::apiResource('wallet-types', WalletTypeController::class);
    Route::controller(TransactionController::class)->group(function () {
        Route::post('/transactions', 'new_transaction');
        Route::get('/transactions', 'all_transactions');
        Route::get('/transactions/{id}', 'get_transaction');
        Route::put('/transactions/{id}', 'update_transaction');
        Route::delete('/transactions/{id}', 'delete_transaction');
        Route::get('/transactions/search', 'search');
    });
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
