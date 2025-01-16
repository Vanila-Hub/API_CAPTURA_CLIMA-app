<?php

use App\Http\Controllers\CiudadModelControler;
use Illuminate\Support\Facades\Route;



Route::get('/api/ciudades', [CiudadModelControler::class, 'index']);
Route::get('/api/clima/{ciudad_nombre}/{fecha_inicio}/{fecha_fin}', [CiudadModelControler::class, 'show']); 
