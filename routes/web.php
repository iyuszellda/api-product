<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return response('<pre>' . Artisan::output() . '</pre>');
    } catch (\Throwable $e) {
        return response('<pre>' . $e->getMessage() . "\n" . $e->getTraceAsString() . '</pre>', 500);
    }
});