<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);

http://localhost:8000/api/v1/categories

Route::get('/test', function () {
    return response()->json(['message' => 'CORS is working!']);
});