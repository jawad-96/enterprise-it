<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CMSController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Lead Capture & CMS routes
Route::post('/contacts', [ContactController::class, 'submit']);
Route::get('/resources', [CMSController::class, 'index']);
Route::get('/resources/{slug}', [CMSController::class, 'show']);
Route::post('/resources', [CMSController::class, 'store']); // Admin backend simulation

// Authentication & Secure Client Portal Routes
// We apply EncryptCookies and StartSession to enable HttpOnly session tracking
Route::middleware([
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Session\Middleware\StartSession::class,
])->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/admin/login', [AuthController::class, 'adminLogin']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/onboard', [AuthController::class, 'onboard']);

    // Admin Onboarding Management
    Route::get('/admin/onboard', [AuthController::class, 'getOnboardRequests']);
    Route::post('/admin/onboard/{id}/approve', [AuthController::class, 'approveOnboardRequest']);

    // Secure Document Repository
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::get('/documents/{id}/download', [DocumentController::class, 'download']);
});
