<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, AccountController, CedulaController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login',  [AuthController::class, 'login']);
    Route::post('register',  [AuthController::class, 'register']);
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', [AuthController::class, 'me']);
    Route::put('update-account/{id}', [AuthController::class, 'updateAccount']);
});

Route::group(['middleware' => 'api', 'prefix' => 'admin'], function () {
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

Route::group(['middleware' => 'api', 'prefix' => 'cedula'], function () {
    Route::get('all-cedula-requests', [CedulaController::class, 'getAllCedulaRequestsByAdmin']);
    Route::get('user-cedula-requests', [CedulaController::class, 'getUserCedulaRequest']);
    Route::post('user-new-cedula',  [CedulaController::class, 'createCedulaRequest']);
    Route::post('upload-user-cedula-requirements/{id}',  [CedulaController::class, 'uploadCedulaRequirements']);
    Route::put('update-request/{id}',  [CedulaController::class, 'updateRequest']);
    Route::put('approve-request/{id}',  [CedulaController::class, 'approveRequest']);
    Route::put('reject-request/{id}',  [CedulaController::class, 'rejectRequest']);
    Route::put('release-request/{id}',  [CedulaController::class, 'markAsReleased']);
});