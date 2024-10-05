<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, AccountController, CedulaController, VerificationController};




// Email verification routes (public routes)
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->middleware('throttle:6,1')
    ->name('verification.send');

// Auth routes (public routes)
Route::group(['prefix' => 'auth'], function () {
    Route::post('login',  [AuthController::class, 'login']);
    Route::post('register',  [AuthController::class, 'register']);
});

// Protected Auth routes (only accessible by authenticated users)
Route::group(['middleware' => 'auth:api', 'prefix' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::put('update-account/{id}', [AuthController::class, 'updateAccount']);
});

// Admin routes (only accessible by authenticated users with 'auth:api' middleware)
Route::group(['middleware' => ['auth:api', 'admin.bypass.verification'], 'prefix' => 'admin'], function () {
    Route::get('total-approved-users', [AccountController::class, 'getTotalApprovedUsers']);
    Route::get('total-suspended-users', [AccountController::class, 'getTotalSuspendedUsers']);
    Route::get('pending-users', [AccountController::class, 'getPendingUsers']);
    Route::get('approved-users', [AccountController::class, 'getApprovedUsers']);
    Route::get('suspended-users', [AccountController::class, 'getSuspendedUsers']);
    Route::put('approve-user/{id}', [AccountController::class, 'approveUser']);
    Route::delete('decline-user/{id}', [AccountController::class, 'declineUser']);
    Route::put('suspend-user/{id}', [AccountController::class, 'suspendUser']);
    Route::put('unsuspend-user/{id}', [AccountController::class, 'unsuspendUser']);
});


// Cedula routes (only accessible by authenticated users with 'auth:api' middleware)
Route::group(['middleware' => ['auth:api', 'verified'], 'prefix' => 'cedula'], function () {
    Route::get('all-cedula-requests', [CedulaController::class, 'getAllCedulaRequestsByAdmin']);
    Route::get('user-cedula-requests', [CedulaController::class, 'getUserCedulaRequest']);
    Route::post('user-new-cedula',  [CedulaController::class, 'createCedulaRequest']);
    Route::post('upload-user-cedula-requirements/{id}',  [CedulaController::class, 'uploadCedulaRequirements']);
    Route::put('update-request/{id}',  [CedulaController::class, 'updateRequest']);
    Route::put('approve-request/{id}',  [CedulaController::class, 'approveRequest']);
    Route::put('reject-request/{id}',  [CedulaController::class, 'rejectRequest']);
    Route::put('release-request/{id}',  [CedulaController::class, 'markAsReleased']);
});
