<?php

namespace App\Console\Commands;

use App\Services\BcvService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateTasaBcv extends Command
{
    /**
     * El nombre y la firma del comando para la consola.
     */
    protected $signature = 'bcv:update-tasa {--force : Forzar actualización ignorando la caché actual}';

    /**
     * Descripción del comando.
     */
    protected $description = 'Realiza web scraping al sitio del BCV para actualizar la tasa oficial USD/VES en caché y BD';

    /**
     * Ejecución del comando Artisan.
     */
    public function handle(BcvService $bcvService): int
    {
        $this->info('Consultando tasa oficial en el portal del Banco Central de Venezuela...');

        if ($this->option('force')) {
            Cache::forget(BcvService::CACHE_KEY);
            $this->warn('Caché previa eliminada. Forzando scraping directo...');
        }

        $tasa = $bcvService->obtenerTasaUsd();

        $this->info("--------------------------------------------------");
        $this->info("Tasa oficial BCV procesada con éxito: {$tasa} VES/USD");
        $this->info("--------------------------------------------------");

        return Command::SUCCESS;
    }
}