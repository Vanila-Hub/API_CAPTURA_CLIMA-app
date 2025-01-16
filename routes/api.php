use Illuminate\Support\Facades\Route;

<?php

use Illuminate\Routing\Route;

Route::get('/welcome', function () {
    return view('welcome');
});