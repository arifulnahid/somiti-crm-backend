<?php

use App\Http\Controllers\API\DepositController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Hello from mobile";
});


Route::post('/login', [UserController::class, 'login']);
Route::post('/auth/me', [UserController::class, 'auth'])->middleware('auth:sanctum');

Route::post('/deposit', DepositController::class)->middleware('auth:sanctum');

