<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BcvService
{
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
        return Cache::remember(self::CACHE_KEY, now()->addHours(12), function () {
            return $this->scrapingTasaBcv();
        });
    }

    /**
     * Realiza el Web Scraping sobre la página oficial del Banco Central de Venezuela.
     */
    public function scrapingTasaBcv(): float
    {
        try {
            // Se inhabilita la verificación SSL debido a problemas frecuentes con los certificados del servidor del BCV
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 15,
            ])->get('https://www.bcv.org.ve/');

            if ($response->successful()) {
                $html = $response->body();

                // Extraer el valor contenido en el contenedor del USD mediante Expresiones Regulares
                if (preg_match('/<div id="dolar">.*?<strong>\s*([\d,.]+)\s*<\/strong>/s', $html, $matches)) {
                    // Convertir formato decimal venezolano (ej. 36,5042) a decimal estándar de BD (36.5042)
                    $tasaLimpia = str_replace('.', '', trim($matches[1])); // Remover punto de miles si existiera
                    $tasaLimpia = str_replace(',', '.', $tasaLimpia);       // Cambiar coma por punto
                    
                    $tasa = round((float) $tasaLimpia, 4);

                    if ($tasa > 0) {
                        $tasa = $tasa;
                        $this->guardarEnBaseDeDatos($tasa);
                        return $tasa;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::channel('single')->error("Error al obtener la tasa oficial del BCV: {$e->getMessage()}");
        }

        // Tasa de respaldo (Fallback) en caso de caída del servicio oficial
        return $this->obtenerUltimaTasaRegistrada() ?? (float) config('app.tasa_bcv_fallback', 800.9);
    }

    /**
     * Registra la tasa de cambio en la base de datos para auditoría e historial.
     */
    private function guardarEnBaseDeDatos(float $tasa): void
    {
        try {
            DB::table('bitacora_auditorias')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'accion' => 'ACTUALIZACION_TASA_BCV',
                'tabla_afectada' => 'sistema_configuracion',
                'datos_nuevos_json' => json_encode(['tasa_usd_ves' => $tasa, 'fuente' => 'BCV_SCRAPING']),
                'ip_address' => '127.0.0.1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning("No se pudo registrar la tasa BCV en la bitácora: {$e->getMessage()}");
        }
    }

    /**
     * Obtiene el último valor válido registrado en la bitácora en caso de fallo del BCV.
     */
    private function obtenerUltimaTasaRegistrada(): ?float
    {
        $ultimoRegistro = DB::table('bitacora_auditorias')
            ->where('accion', 'ACTUALIZACION_TASA_BCV')
            ->latest()
            ->first();

        if ($ultimoRegistro && !empty($ultimoRegistro->datos_nuevos_json)) {
            $data = json_decode($ultimoRegistro->datos_nuevos_json, true);
            return $data['tasa_usd_ves'] ?? null;
        }

        return null;
    }
}