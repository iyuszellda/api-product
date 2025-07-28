<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SourceImageController;

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('images', SourceImageController::class);

Route::post('/refresh-products', function (Request $request) {
    if ($request->bearerToken() !== config('app.cron_secret')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // clear all current data in products and product_images
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    DB::table('product_images')->truncate();
    DB::table('products')->truncate();

    // refill all data from seeds table
    DB::statement('INSERT INTO products (title, slug, price, description, category_id, created_at, updated_at)
                   SELECT title, slug, price, description, category_id, NOW(), NOW() FROM seeds_products');

    DB::statement('INSERT INTO product_images (product_id, url, created_at, updated_at)
                   SELECT product_id, url, NOW(), NOW() FROM seeds_product_images ORDER BY product_id ASC');

    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    return response()->json(true);
});
