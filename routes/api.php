<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'HEALTHY']);
});

Route::prefix('user')->group(function () {
    Route::post('/create', [UserController::class, 'create']);
});

Route::prefix('account')->group(function () {
    Route::post('/create', [AccountController::class, 'create']);
});

Route::prefix('transfer')->group(function () {
    Route::post('/', [TransactionController::class, 'transfer']);
});
