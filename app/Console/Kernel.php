<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define los comandos Artisan.
     */

    /**
     * Define las tareas programadas.
     */
    protected function schedule(Schedule $schedule)
    {
        // Tarea personalizada para guardar pronostico de la api cada 10 minutos
        $schedule->command('weatherdata:save')->everyTenMinutes();
    }
    

    /**
     * Registra los eventos para los comandos Artisan.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
