<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;

Route::post('register', [AuthController::class, 'register'])->middleware([HandlePrecognitiveRequests::class]);
Route::post('login', [AuthController::class, 'login'])->middleware([HandlePrecognitiveRequests::class]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logOut']);

    Route::group(['prefix' => 'authors'], function () {
        Route::get('/', [AuthorController::class, 'index']);
        Route::get('/{author_id}', [AuthorController::class, 'show']);
        Route::post('/store', [AuthorController::class, 'store'])->middleware([HandlePrecognitiveRequests::class]);
        Route::put('/update/{author_id}', [AuthorController::class, 'update'])->middleware([HandlePrecognitiveRequests::class]);
        Route::delete('/delete/{author_id}', [AuthorController::class, 'destroy']);
    });

    Route::group(['prefix' => 'books'], function () {
        Route::get('/', [BookController::class, 'index']);
        Route::get('/{book_id}', [BookController::class, 'show']);
        Route::post('/store', [BookController::class, 'store'])->middleware([HandlePrecognitiveRequests::class]);
        Route::put('/update/{book_id}', [BookController::class, 'update'])->middleware([HandlePrecognitiveRequests::class]);
        Route::delete('/delete/{book_id}', [BookController::class, 'destroy']);
    });
});
