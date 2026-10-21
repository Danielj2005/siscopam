<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\PagoReportado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PagoConciliacionService
{
    /**
     * Aprueba un pago reportado y renueva la fecha de vencimiento del cliente.
     */
    public function aprobarPago(PagoReportado $pago, User $operador): PagoReportado
    {
        return DB::transaction(function () use ($pago, $operador) {
            if ($pago->estatus !== 'PENDIENTE') {
                throw new \DomainException('Este pago ya fue procesado anteriormente.');
            }

            /** @var Cliente $cliente */
            $cliente = Cliente::where('id', $pago->cliente_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Calcular nueva fecha de vencimiento
            $fechaBase = ($cliente->fecha_vencimiento && $cliente->fecha_vencimiento->isFuture())
                ? $cliente->fecha_vencimiento
                : now();

            $nuevaFechaVencimiento = $fechaBase->copy()->addDays($pago->dias_renovacion);

            // Actualizar cliente
            $cliente->update([
                'fecha_vencimiento' => $nuevaFechaVencimiento,
                'activo' => true,
            ]);

            // Marcar el pago como APROBADO
            $pago->update([
                'estatus' => 'APROBADO',
                'aprobado_por_user_id' => $operador->id,
                'fecha_revision' => now(),
            ]);

            return $pago->fresh(['cliente', 'revisor']);
        });
    }

    /**
     * Rechaza un pago reportado con un motivo especificado.
     */
    public function rechazarPago(PagoReportado $pago, User $operador, string $motivo): PagoReportado
    {
        if ($pago->estatus !== 'PENDIENTE') {
            throw new \DomainException('Este pago ya fue procesado anteriormente.');
        }

        $pago->update([
            'estatus' => 'RECHAZADO',
            'motivo_rechazo' => $motivo,
            'aprobado_por_user_id' => $operador->id,
            'fecha_revision' => now(),
        ]);

        return $pago->fresh(['cliente', 'revisor']);
    }
}