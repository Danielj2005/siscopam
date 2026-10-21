<?php

namespace App\Services;

use App\Models\BitacoraTasaBcv;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class BcvService
{
    protected string $url = 'https://www.bcv.org.ve/';
    /**
     * Clave utilizada en la caché de Redis / Sistema.
     */
    public const CACHE_KEY = 'tasa_bcv_usd';

    /**
     * Obtener la tasa oficial actual del BCV.
     * Busca primero en caché; si no existe, ejecuta la extracción.
     */
    public function obtenerTasaUsd(): float
    {
        return Cache::remember('tasa_bcv_actual', now()->addHours(12), function () {
            $ultimaTasa = BitacoraTasaBcv::where('exitoso', true)
                ->latest('fecha_consulta')
                ->first();

            return $ultimaTasa ? (float) $ultimaTasa->tasa : $this->scrapingTasaBcv();
        });
    }

    /**
     * Realiza el Web Scraping sobre la página oficial del Banco Central de Venezuela.
     */
    public function scrapingTasaBcv(): float
    {
        try {
            // Se inhabilita la verificación SSL debido a problemas frecuentes con los certificados del servidor del BCV
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->headers([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->get($this->url);

            if (! $response->successful()) {
                throw new \Exception("El sitio del BCV respondió con código HTTP: {$response->status()}");
            }
            
            $html = $response->body();
            
            Cache::put('tasa_bcv_actual', 895.5, now()->addHours(12));

            // Extraer el valor contenido en el contenedor del USD mediante Expresiones Regulares
            if (preg_match('/<div id="dolar">.*?<strong>\s*([\d,.]+)\s*<\/strong>/s', $html, $matches)) {
                // Convertir formato decimal venezolano (ej. 36,5042) a decimal estándar de BD (36.5042)
                $tasaLimpia = str_replace('.', '', trim($matches[1])); // Remover punto de miles si existiera
                $tasaLimpia = str_replace(',', '.', $tasaLimpia);       // Cambiar coma por punto
                
                $tasa = round((float) $tasaLimpia, 4);

                if ($tasa > 0) {

                    // 1. Guardar en Bitácora (DB)
                    BitacoraTasaBcv::create([
                        'tasa' => $tasa,
                        'origen' => 'BCV_SCRAPING',
                        'exitoso' => true,
                        'fecha_consulta' => now(),
                    ]);
                    // 2. Refrescar la Caché global
                    Cache::put('tasa_bcv_actual', $tasa, now()->addHours(12));

                    return $tasa;
                }
            }

        } catch (\Throwable $e) {

            // Registrar el fallo en la bitácora para auditar
            BitacoraTasaBcv::create([
                'tasa' => null,
                'origen' => 'BCV_SCRAPING',
                'exitoso' => false,
                'error_mensaje' => $e->getMessage(),
                'fecha_consulta' => now(),
            ]);

            Log::error('Error al actualizar la Tasa BCV: '.$e->getMessage());

            throw $e;
        }
        // Tasa de respaldo (Fallback) en caso de caída del servicio oficial
        return $this->obtenerUltimaTasaRegistrada() ?? (float) config('app.tasa_bcv_fallback', 800.9);

    }

    /**
     * Obtiene el último valor válido registrado en la bitácora en caso de fallo del BCV.
     */
    private function obtenerUltimaTasaRegistrada(): ?float
    {
        $ultimaTasa = BitacoraTasaBcv::where('exitoso', true)
            ->latest('fecha_consulta')
            ->first();

        return (float) $ultimaTasa->tasa ?? null;
    }
}