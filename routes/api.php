<?php

use App\Http\Controllers\Authentication\AuthControler;
use App\Http\Controllers\CiudadModelControler;
use App\Http\Controllers\PronosticoModelControler;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthControler::class, 'login']);
    Route::post('/signup', [AuthControler::class, 'signUp']);
    Route::post('/logout', [AuthControler::class, 'logout'])->middleware('auth:api');
    Route::get('/user', [AuthControler::class, 'user'])->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/ciudades', [CiudadModelControler::class,'index']);
    Route::get('/pronostico/{ciudad_nombre}', [PronosticoModelControler::class, 'obtenerPronosticoPorciudad']);
    Route::get('/clima/{ciudad_nombre}/{fecha_inicio}/{fecha_fin}', [PronosticoModelControler::class, 'show']); 
});

// Route::get('/ciudades', [CiudadModelControler::class, 'index']);
Route::get('/ciudades_store', function () {
    Artisan::call('ciudades:store');
    return response('Cron ejecutado pronosticos guardados', 200);
});