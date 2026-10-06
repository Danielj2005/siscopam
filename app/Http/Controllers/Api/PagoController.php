<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\HistorialPago;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PagoController extends Controller
{
    /**
     * Registrar un pago multimoneda y renovar/activar la membresía del cliente.
     */
    public function store(Request $request)
    {
        // 1. Validar Petición
        $validated = $request->validate([
            'cliente_id' => ['required', 'uuid', 'exists:clientes,id'],
            'plan_id' => ['required', 'uuid', 'exists:planes,id'],
            'monto_recibido' => ['required', 'numeric', 'min:0.01'],
            'moneda_pago' => ['required', Rule::in(['USD', 'VES'])],
            'tasa_bcv' => ['required', 'numeric', 'min:0.0001'],
            'metodo_pago' => ['required', Rule::in(['PAGO_MOVIL', 'EFECTIVO_USD', 'EFECTIVO_VES', 'ZELLE', 'PUNTO'])],
            'referencia' => ['nullable', 'string', 'max:50'],
            'banco_origen' => ['nullable', 'string', 'max:50'],
            'banco_destino' => ['nullable', 'string', 'max:50'],
            'notas' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        // 2. Transacción de Base de Datos Atómica
        return DB::transaction(function () use ($validated, $user) {
            $cliente = Cliente::findOrFail($validated['cliente_id']);
            $plan = Plan::findOrFail($validated['plan_id']);

            $tasaBcv = (float) $validated['tasa_bcv'];
            $montoRecibido = (float) $validated['monto_recibido'];

            // 3. Conversión Multimoneda Segura
            if ($validated['moneda_pago'] === 'USD') {
                $montoUsd = $montoRecibido;
                $montoVes = round($montoUsd * $tasaBcv, 2);
            } else {
                $montoVes = $montoRecibido;
                $montoUsd = round($montoVes / $tasaBcv, 2);
            }

            // 4. Registrar el Pago en Historial
            $pago = HistorialPago::create([
                'empresa_id' => $user->empresa_id ?? $cliente->empresa_id,
                'sede_id' => $user->sede_id ?? $cliente->sede_id,
                'cliente_id' => $cliente->id,
                'plan_id' => $plan->id,
                'usuario_registro_id' => $user->id,
                'monto_usd' => $montoUsd,
                'monto_ves' => $montoVes,
                'tasa_bcv_usada' => $tasaBcv,
                'metodo_pago' => $validated['metodo_pago'],
                'referencia' => $validated['referencia'] ?? null,
                'banco_origen' => $validated['banco_origen'] ?? null,
                'banco_destino' => $validated['banco_destino'] ?? null,
                'estatus' => 'COMPLETADO',
                'notas' => $validated['notas'] ?? null,
            ]);

            // 5. Calcular Renovación/Extensión de Fecha de Vencimiento
            $hoy = Carbon::today();
            $fechaBase = ($cliente->fecha_vencimiento && $cliente->fecha_vencimiento->isFuture()) 
                ? $cliente->fecha_vencimiento 
                : $hoy;

            $nuevaFechaVencimiento = $fechaBase->copy()->addDays($plan->duracion_dias);

            // 6. Actualizar Estatus del Cliente
            $cliente->update([
                'estado' => 'ACTIVO',
                'fecha_vencimiento' => $nuevaFechaVencimiento,
            ]);

            return response()->json([
                'message' => 'Pago registrado con éxito y membresía activada.',
                'pago' => $pago,
                'cliente' => [
                    'id' => $cliente->id,
                    'nombre_completo' => "{$cliente->nombre} {$cliente->apellido}",
                    'estado' => $cliente->estado,
                    'fecha_vencimiento' => $cliente->fecha_vencimiento->format('Y-m-d'),
                ]
            ], 201);
        });
    }
}