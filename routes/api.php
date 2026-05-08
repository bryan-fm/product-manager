<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class)
    ->names('api.products');
});

Route::get('/', function () {
    return response()->json(['message' => 'API está funcionando!']);
});