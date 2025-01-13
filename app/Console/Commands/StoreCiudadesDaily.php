<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
        $controller = new CiudadModelControler();
        $controller->store();
        $this->info('Datos de las ciudades guardados exitosamente.');
    }
}
