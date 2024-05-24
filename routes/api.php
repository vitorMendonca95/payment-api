<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
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
