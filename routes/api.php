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

// Route::get('/pronosticos_weather_store', function () {
//     Artisan::call('pronosticosweather:store');
//     return response('Cron ejecutado pronosticos actuales guardados', 200);
// });

// Route::get('/ciudades_store', function () {
//     Artisan::call('db:seed', ['--class' => 'CiudadesSeeder']);
//     return response('Cron ejecutado: ciudades guardadas', 200);
// });

// Route::get('/historicos_store', function () {
//     Artisan::call('db:seed', ['--class' => 'PronosticosSeeder']);
//     return response('Cron ejecutado datos historicos guardados', 200);
// });