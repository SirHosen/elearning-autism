<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

if (app()->environment('local')) {
    Route::get('/dev/styleguide', function () {
        return view('dev.styleguide');
    });
}
