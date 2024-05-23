<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'HEALTHY']);
});

Route::prefix('user')->group(function () {
    Route::post('/register', [UserController::class, 'create']);
});
