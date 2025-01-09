<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define los comandos Artisan.
     */
    protected $commands = [
        \App\Console\Commands\StoreCiudadesDaily::class,
    ];
    
    

    /**
     * Define las tareas programadas.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ciudades:store')->dailyAt('23:59');
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
