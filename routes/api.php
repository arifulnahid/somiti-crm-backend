<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\MemberController;
use App\Http\Controllers\API\NomineeController;
use App\Http\Controllers\API\SocietyController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DepositController;
use App\Http\Controllers\API\LoanController;
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
     Route::get('/{user}', 'show');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
});

Route::prefix('address')->controller(AddressController::class)->group(function () {
    Route::get('/', 'index')->can('viewAny,App\Models\Address');
    Route::post('/', 'store');
    Route::get('/get-divisons-with-districts', 'getDivisionsAndDistricts');
    Route::get('/divisions', 'divisions');
    Route::patch('/{address}', 'update');
    Route::get('/{address}', 'show')->can('view,address');
    Route::delete('/{address}', 'destroy');
});

Route::prefix('branches')->controller(BranchController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{branch}', 'show');
    Route::patch('/{branch}', 'update');
});

Route::apiResource('members', MemberController::class);
Route::apiResource('societies', SocietyController::class);
Route::apiResource('nominees', NomineeController::class);
Route::apiResource('transactions', TransactionController::class);


Route::get('/dashboard/stats', DashboardController::class)
     ->middleware(['auth:sanctum', 'can:viewDashboard,App\Models\Transaction']);


// Deposit Routes
Route::apiResource('deposits', DepositController::class);
Route::get('deposits/upcoming/deadlines', [DepositController::class, 'upcomingDeadlines'])
    ->name('deposits.upcoming');

// Loan Routes
Route::apiResource('loans', LoanController::class);
Route::get('loans/{loan}/summary', [LoanController::class, 'calculateSummary'])
    ->name('loans.summary');