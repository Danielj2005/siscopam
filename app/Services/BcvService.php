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

            return $ultimaTasa ? (float) $ultimaTasa : $this->scrapingTasaBcv();
        });
    }

    /**
     * Realiza el Web Scraping sobre la página oficial del Banco Central de Venezuela.
     */
    public function scrapingTasaBcv(): float
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->get('https://www.bcv.org.ve/');

            if ($response->successful()) {
                $html = $response->body();
                if (preg_match('/<div id="dolar".*?<strong>\s*([\d,.]+)\s*<\/strong>/s', $html, $matches)) {
                    $tasa = (float) str_replace(',', '.', str_replace('.', '', trim($matches[1])));
                    if ($tasa > 0) {
                        Cache::forever('tasa_bcv_actual', $tasa);
                        \App\Models\BitacoraTasaBcv::create([
                            'tasa' => $tasa,
                            'origen' => 'BCV_SCRAPING',
                            'exitoso' => true,
                            'fecha_consulta' => now(),
                        ]);
                        return $tasa;
                    }
                }
            }
            throw new \Exception("No se pudo extraer la tasa del HTML del BCV.");
        } catch (\Throwable $e) {
            Log::error("Fallo BCV: " . $e->getMessage());
            return $this->obtenerTasaUsd(); // Retorna la última válida en caché
        }
    }

    /**
     * Obtiene el último valor válido registrado en la bitácora en caso de fallo del BCV.
     */
    private function obtenerUltimaTasaRegistrada(): ?float
    {
        $ultimaTasa = BitacoraTasaBcv::where('exitoso', true)
            ->latest('fecha_consulta')
            ->first();

        return (float) $ultimaTasa ?? null;
    }
}