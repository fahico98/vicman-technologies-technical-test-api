<?php

use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register'])->name('register')->middleware([HandlePrecognitiveRequests::class]);
Route::post('login', [AuthController::class, 'login'])->name('login')->middleware([HandlePrecognitiveRequests::class]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me'])->name('me');
    Route::post('logout', [AuthController::class, 'logOut'])->name('logout');
});
