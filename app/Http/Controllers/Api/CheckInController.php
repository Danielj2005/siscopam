<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Cliente;
use App\Services\CheckInService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CheckInController extends Controller
{
    protected CheckInService $checkInService;

    public function __construct(CheckInService $checkInService)
    {
        $this->checkInService = $checkInService;
    }

    /**
     * Escanear QR e ingresar al establecimiento.
     */
    public function escanearQr(Request $request)
    {
        $validated = $request->validate([
            'qr_data' => ['required', 'string'],
            'sede_id' => ['required', 'uuid', 'exists:sedes,id'],
            'metodo_validacion' => ['nullable', Rule::in(['QR_BIOMETRICO', 'CEDULA_MANUAL', 'FACE_ID', 'DISPOSITIVO_LOCAL'])],
        ]);

        try {
            $data = $this->checkInService->decodificarQr($validated['qr_data']);

            $detallesDispositivo = [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'scanner_id' => $request->header('X-Scanner-ID', 'POS-WEB'),
            ];

            $asistencia = $this->checkInService->procesarCheckIn(
                clienteId: $data['cliente_id'],
                sedeId: $validated['sede_id'],
                empresaId: $request->user()->empresa_id,
                metodoValidacion: $validated['metodo_validacion'] ?? 'QR_BIOMETRICO',
                detallesDispositivo: $detallesDispositivo
            );

            $esPermitido = $asistencia->estatus === 'PERMITIDO';

            return response()->json([
                'acceso' => $esPermitido,
                'estatus' => $asistencia->estatus,
                'mensaje' => $esPermitido ? 'Acceso PERMITIDO. ¡Bienvenido!' : "Acceso DENEGADO: {$asistencia->estatus}",
                'asistencia' => $asistencia,
            ], $esPermitido ? 200 : 403);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'acceso' => false,
                'mensaje' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Check-in manual mediante Cédula en Recepción.
     */
    public function checkInManual(Request $request)
    {
        $validated = $request->validate([
            'cedula' => ['required', 'string'],
            'sede_id' => ['required', 'uuid', 'exists:sedes,id'],
        ]);

        $cliente = Cliente::where('empresa_id', $request->user()->empresa_id)
            ->where('cedula', $validated['cedula'])
            ->firstOrFail();

        $detallesDispositivo = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'operador_id' => $request->user()->id,
        ];

        $asistencia = $this->checkInService->procesarCheckIn(
            clienteId: $cliente->id,
            sedeId: $validated['sede_id'],
            empresaId: $request->user()->empresa_id,
            metodoValidacion: 'CEDULA_MANUAL',
            detallesDispositivo: $detallesDispositivo
        );

        $esPermitido = $asistencia->estatus === 'PERMITIDO';

        return response()->json([
            'acceso' => $esPermitido,
            'estatus' => $asistencia->estatus,
            'mensaje' => $esPermitido ? "Acceso autorizado para {$cliente->nombre}" : "Acceso DENEGADO: {$asistencia->estatus}",
            'asistencia' => $asistencia,
        ], $esPermitido ? 200 : 403);
    }

    /**
     * Obtener el token QR dinámico para el usuario/cliente autenticado.
     */
    public function miCodigoQr(Request $request)
    {
        $cliente = $request->user()->cliente;

        if (! $cliente) {
            return response()->json(['error' => 'No hay perfil de cliente vinculado a esta cuenta'], 404);
        }

        $qrToken = $this->checkInService->generarTokenQr($cliente);

        return response()->json([
            'qr_token' => $qrToken,
            'cliente' => [
                'nombre' => $cliente->nombre,
                'cedula' => $cliente->cedula,
                'estado_membresia' => $cliente->fecha_vencimiento && now()->gt($cliente->fecha_vencimiento) ? 'VENCIDA' : 'ACTIVA',
            ],
        ]);
    }
}