<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

// Authentication Routes (public, no authentication required)

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

// Email Verification Routes (public)
Route::post('email/verification-notification', [AuthController::class, 'resendVerificationEmail']);
Route::post('/verification/send', [AuthController::class, 'sendVerificationEmail']);

// Authenticated User Route (requires authentication)
Route::get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum'])->group(function () {
// Post Routes (public for index, authenticated for create, update, delete)
    Route::get('posts', [PostController::class, 'index']);
    Route::get('user/posts', [PostController::class, 'userPosts']);
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{post}', [PostController::class, 'update']);
    Route::delete('posts/{post}', [PostController::class, 'destroy']);
});
