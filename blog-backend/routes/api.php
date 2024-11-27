<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

// CSRF Token Route (public, no authentication required)
Route::get('sanctum/csrf-cookie', [CsrfCookieController::class, 'show'])->name('csrf');


// Authentication Routes (public, no authentication required)
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Email Verification Routes (public)
Route::middleware('auth:sanctum')->post('email/verification-notification', [AuthController::class, 'resendVerificationEmail']);
Route::post('/verification/send', [AuthController::class, 'sendVerificationEmail']);

// Authenticated User Route (requires authentication)
Route::get('/user', function (Request $request) {
    return "aa";
});

// Post Routes (public for index, authenticated for create, update, delete)
Route::middleware('auth:sanctum')->get('posts', [PostController::class, 'index']);
Route::middleware('auth:sanctum')->get('user/posts', [PostController::class, 'userPosts']);
Route::middleware('auth:sanctum')->post('posts', [PostController::class, 'store']);
Route::middleware('auth:sanctum')->put('posts/{post}', [PostController::class, 'update']);
Route::middleware('auth:sanctum')->delete('posts/{post}', [PostController::class, 'destroy']);
