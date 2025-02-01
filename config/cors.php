<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Options
    |--------------------------------------------------------------------------
    |
    | This configuration file controls CORS settings for your application. By
    | default, it allows all methods, origins, and headers to ensure flexibility.
    | Feel free to modify the configuration as needed.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Rutas de la API que tienen habilitado CORS
    'allowed_methods' => ['*'], // Permite todos los métodos HTTP (GET, POST, PUT, DELETE, OPTIONS)
    'allowed_origins' => ['*'], // Permite todos los orígenes, ideal para desarrollo
    'allowed_origins_patterns' => [], // Puedes agregar patrones si necesitas filtrar orígenes específicos
    'allowed_headers' => ['*'], // Permite todos los encabezados
    'exposed_headers' => ['*'],
    'max_age' => 0,
    'supports_credentials' => true, // Si necesitas que los navegadores envíen cookies, cambia esto a true
];
