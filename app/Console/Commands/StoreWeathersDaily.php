<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log; // Importa la clase Log
use App\Http\Controllers\CiudadModelControler;

class StoreWeathersDaily extends Command
{
    protected $signature = 'weatherdata:save';
    protected $description = 'Guarda datos actuales del clima por cada ciudad cada 5 minutos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            // Llamada al controlador
            $controller = new CiudadModelControler();
            $controller->generate_cities_forecast();

            // Mensaje de éxito en consola
            $this->info('Datos actuales del clima de las ciudades guardados exitosamente.');

            // Registrar el evento en el archivo de log
            Log::info('Comando weatherdata:save ejecutado correctamente. Datos capturados y  guardados en la Base de Datos.');
        } catch (\Exception $e) {
            // Manejo de errores y registro en logs
            Log::error('Error al ejecutar el comando weatherdata:save ' . $e->getMessage());
            $this->error('Error al guardar los datos: ' . $e->getMessage());
        }
    }
}
