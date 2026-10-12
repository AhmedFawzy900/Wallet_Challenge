<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserTransactionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user-transactions', [UserTransactionsController::class, 'create']);
});
