<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/clear-cache', function() {
    Artisan::call('config:clear');
    return "Config cache cleared!";
});