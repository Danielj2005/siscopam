<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define la programación de comandos del sistema.
     */
    protected function schedule(Schedule $schedule): void
    {
        // El BCV publica la tasa oficial al final de la tarde.
        // Se ejecuta de lunes a viernes a las 17:00 y 18:00 (Hora de Venezuela).
        $schedule->command('bcv:update-tasa --force')
            ->weekdays()
            ->at('17:00')
            ->timezone('America/Caracas')
            ->appendOutputTo(storage_path('logs/bcv_cron.log'));

        $schedule->command('bcv:update-tasa --force')
            ->weekdays()
            ->at('18:00')
            ->timezone('America/Caracas')
            ->appendOutputTo(storage_path('logs/bcv_cron.log'));
        $schedule->command('membresias:notificar-vencimientos')->dailyAt('08:00');

    }

    /**
     * Registra los comandos para la aplicación.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}