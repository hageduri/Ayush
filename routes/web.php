<?php

use Illuminate\Support\Facades\Route;

Route::get('/Home', function () {
    return view('welcome');
});
