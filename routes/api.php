<?php

use App\Http\Controllers\Authentication\AuthControler;
use App\Http\Controllers\CiudadModelControler;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthControler::class, 'login']);
    Route::post('/signup', [AuthControler::class, 'signUp']);
    Route::post('/logout', [AuthControler::class, 'logout'])->middleware('auth:api');
    Route::get('/user', [AuthControler::class, 'user'])->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/ciudades', [CiudadModelControler::class,'index']);
    Route::get('/clima/{ciudad_nombre}/{fecha_inicio}/{fecha_fin}', [CiudadModelControler::class, 'show']); 
});

// Route::get('/ciudades', [CiudadModelControler::class, 'index']);

