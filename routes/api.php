<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// -------------------------------------------------------------
// Public:

Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
    ]);
})->middleware('throttle:5,1');

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

// -------------------------------------------------------------
// Protected:

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->prefix('user')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'user']);
    Route::patch('/', [UserController::class, 'update']);
    Route::delete('/', [UserController::class, 'deactivate']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/membership-status', [UserController::class, 'membershipStatus']);
});

