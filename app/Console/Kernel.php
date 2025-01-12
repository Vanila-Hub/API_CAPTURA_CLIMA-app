<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define los comandos Artisan.
     */
    // protected $commands = [
    //     Commands\StoreCiudadesDaily::class, 
    // ];    

    /**
     * Define las tareas programadas.
     */
    protected function schedule(Schedule $schedule)
    {
        // Tarea existente
        $schedule->command('inspire')->hourly();
    
        // Tarea personalizada
        $schedule->command('ciudades:store')->everyMinute();
    
        // Otra tarea personalizada
        $schedule->call(function () {
            Log::info('Tarea personalizada ejecutada.');
        })->dailyAt('12:00');
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
