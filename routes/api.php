<?php

use App\Http\Controllers\CiudadModelControler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthControler;

Route::post('login', [AuthControler::class, 'login']);
Route::post('signup', [AuthControler::class, 'signUp']);
Route::post('logout', [AuthControler::class, 'logout'])->middleware('auth:api');
Route::get('user', [AuthControler::class, 'user'])->middleware('auth:api');

Route::middleware('authToken')->group(function () {
    Route::get('/ciudades', [CiudadModelControler::class,'index']);
    Route::get('/clima/{ciudad_nombre}/{fecha_inicio}/{fecha_fin}', [CiudadModelControler::class, 'show']); 
});

// Route::get('/ciudades', [CiudadModelControler::class, 'index']);

