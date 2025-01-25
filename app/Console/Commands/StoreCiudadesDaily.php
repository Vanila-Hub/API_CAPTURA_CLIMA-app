<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log; // Importa la clase Log
use App\Http\Controllers\CiudadModelControler;

class StoreCiudadesDaily extends Command
{
    protected $signature = 'pronosticosweather:store';
    protected $description = 'Guarda datos de los pronosticos de las ciudades cada 10 minutos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            // Llamada al controlador
            $controller = new CiudadModelControler();
            $controller->store();

            // Mensaje de éxito en consola
            $this->info('Pronosticos de las ciudades guardados exitosamente.');

            // Registrar el evento en el archivo de log
            Log::info('Comando pronosticosweather:store ejecutado correctamente. Datos capturados y  guardados exitosamente.');
        } catch (\Exception $e) {
            // Manejo de errores y registro en logs
            Log::error('Error al ejecutar el comando pronosticosweather:store: ' . $e->getMessage());
            $this->error('Error al guardar los datos: ' . $e->getMessage());
        }
    }
}
