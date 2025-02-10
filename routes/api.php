/**
 * Configuración de Rutas API
 * 
 * Este archivo define las rutas API para los endpoints de autenticación y recursos de la aplicación.
 * 
 * Rutas de Autenticación:
 * - POST /api/auth/login - Endpoint de inicio de sesión
 * - POST /api/auth/signup - Registro de nuevo usuario
 * - POST /api/auth/logout - Cierre de sesión (requiere autenticación)
 * - POST /api/auth/user - Obtener información del usuario autenticado (requiere autenticación)
 * 
 * Rutas Protegidas (requieren autenticación):
 * Endpoints de Ciudades:
 * - GET /api/ciudades - Listar todas las ciudades
 * - POST /api/ciudades - Crear nueva ciudad
 * - GET /api/ciudades/{id} - Obtener detalles de ciudad específica
 * - PUT /api/ciudades/{id} - Actualizar información de ciudad
 * - DELETE /api/ciudades/{id} - Eliminar ciudad
 * 
 * Endpoints de Pronóstico del tiempo:
 * - GET /api/pronostico/{ciudad_nombre} - Obtener pronóstico por nombre de ciudad
 * - GET /api/clima/{ciudad_nombre}/{fecha_inicio}/{fecha_fin} - Obtener datos climáticos por rango de fechas
 * 
 * Todas las rutas protegidas requieren un token de autenticación API válido
 */
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
    Route::post('/user', [AuthControler::class, 'user'])->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/ciudades', [CiudadModelControler::class,'index']);
    Route::get('/pronostico/{ciudad_nombre}', [PronosticoModelControler::class, 'obtenerPronosticoPorciudad']);
    Route::get('/clima/{ciudad_nombre}/{fecha_inicio}/{fecha_fin}', [PronosticoModelControler::class, 'show']); 
    Route::post('/ciudades', [CiudadModelControler::class, 'store']);
    Route::get('/ciudades/{id}', [CiudadModelControler::class, 'show']);
    Route::put('/ciudades/{id}', [CiudadModelControler::class, 'update']);
    Route::delete('/ciudades/{id}', [CiudadModelControler::class, 'destroy']);
});
