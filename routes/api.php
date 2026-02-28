<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\MemberController;
use App\Http\Controllers\API\NomineeController;
use App\Http\Controllers\API\SocietyController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/auth/me', UserController::class);

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('auth/me', 'auth')->middleware('auth:sanctum');
    Route::get('/', 'index');
    Route::post('/register', 'store');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
});

Route::prefix('address')->controller(AddressController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::patch('/{address}', 'update');
    Route::get('/{address}', 'show');
    Route::delete('/{address}', 'destroy');
});

Route::prefix('branch')->controller(BranchController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/create', 'store');
});

Route::apiResource('members', MemberController::class);
Route::apiResource('societies', SocietyController::class);
Route::apiResource('nominees', NomineeController::class);
Route::apiResource('transactions', TransactionController::class);
