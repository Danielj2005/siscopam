<?php

namespace App\Services;

use App\Models\Asistencia;
use App\Models\Cliente;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CheckInService
{
    /**
     * Generar Token QR cifrado para el cliente.
     */
    public function generarTokenQr(Cliente $cliente): string
    {
        return Crypt::encryptArray([
            'cliente_id' => $cliente->id,
            'empresa_id' => $cliente->empresa_id,
            'timestamp' => now()->timestamp,
        ]);
    }

    /**
     * Decodificar token cifrado del QR.
     */
    public function decodificarQr(string $qrToken): array
    {
        try {
            return Crypt::decryptArray($qrToken);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('El código QR provisto es inválido o ha expirado.');
        }
    }

    /**
     * Validar estatus del cliente y registrar el intento de ingreso.
     */
    public function procesarCheckIn(
        string $clienteId,
        string $sedeId,
        string $empresaId,
        string $metodoValidacion,
        array $detallesDispositivo = []
    ): Asistencia {
        return DB::transaction(function () use ($clienteId, $sedeId, $empresaId, $metodoValidacion, $detallesDispositivo) {
            $cliente = Cliente::where('empresa_id', $empresaId)->findOrFail($clienteId);

            $estatusAcceso = 'PERMITIDO';

            // 1. Verificación de reglas sobre el Cliente
            if (isset($cliente->activo) && ! $cliente->activo) {
                $estatusAcceso = 'DENEGADO_INACTIVO';
            } elseif (isset($cliente->fecha_vencimiento) && now()->gt($cliente->fecha_vencimiento)) {
                $estatusAcceso = 'DENEGADO_VENCIDO';
            }

            // 2. Registro del evento de asistencia
            return Asistencia::create([
                'empresa_id' => $empresaId,
                'sede_id' => $sedeId,
                'cliente_id' => $cliente->id,
                'metodo_validacion' => $metodoValidacion, // 'QR_BIOMETRICO', 'CEDULA_MANUAL', 'FACE_ID', 'DISPOSITIVO_LOCAL'
                'estatus' => $estatusAcceso,             // 'PERMITIDO', 'DENEGADO_VENCIDO', 'DENEGADO_INACTIVO'
                'detalles_dispositivo_json' => $detallesDispositivo,
                'fecha_hora' => now(),
            ])->load(['cliente', 'sede']);
        });
    }
}