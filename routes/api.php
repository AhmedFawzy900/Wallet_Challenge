<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserTransactionsController;
use App\Http\Controllers\API\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user-transactions', [UserTransactionsController::class, 'create']);
});

Route::prefix('webhook')->group(function(){
    Route::post('/paytech', [WebhookController::class, 'handlePayTech'])
    ->middleware('webhook.validate:paytech');
    Route::post('/acme', [WebhookController::class, 'handleAcme'])
    ->middleware('webhook.validate:acme');
});
