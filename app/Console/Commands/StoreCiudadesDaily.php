<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log; // Importa la clase Log
use App\Http\Controllers\CiudadModelControler;
use Illuminate\Container\Attributes\Log;

class StoreCiudadesDaily extends Command
{
    protected $signature = 'ciudades:store';
    protected $description = 'Guarda datos de las ciudades diariamente';

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
            $this->info('Datos de las ciudades guardados exitosamente.');

            // Registrar el evento en el archivo de log
            Log::info('Comando ciudades:store ejecutado correctamente. Datos guardados exitosamente.');
        } catch (\Exception $e) {
            // Manejo de errores y registro en logs
            Log::error('Error al ejecutar el comando ciudades:store: ' . $e->getMessage());
            $this->error('Error al guardar los datos: ' . $e->getMessage());
        }
    }
}
