<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

Route::get('/', function () {
    if (App::environment('production')) {
        abort(404);
    }

    return response()->json(['message' => 'API is running'], 200);
});
