<?php

namespace App\Services;

use App\Models\CierreCaja;
use App\Models\Venta;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CierreCajaService
{
    /**
     * Abrir un turno de caja para la sede especificada.
     */
    public function abrirCaja(User $user, string $sedeId, float $montoInicialUsd = 0, float $montoInicialVes = 0): CierreCaja
    {
        $cajaAbierta = CierreCaja::where('sede_id', $sedeId)
            ->where('estatus', 'ABIERTA')
            ->exists();

        if ($cajaAbierta) {
            throw new \DomainException('Ya existe una caja abierta en esta sede.');
        }

        return CierreCaja::create([
            'empresa_id' => $user->empresa_id,
            'sede_id' => $sedeId,
            'user_id' => $user->id,
            'fecha_apertura' => now(),
            'monto_inicial_usd' => $montoInicialUsd,
            'monto_inicial_ves' => $montoInicialVes,
            'estatus' => 'ABIERTA',
        ]);
    }

    /**
     * Calcular los totales generados por el sistema durante la caja activa.
     */
    public function calcularResumenCaja(CierreCaja $caja): array
    {
        // Consultar sumas de ventas realizadas en esta sede durante la apertura actual
        $ventas = DB::table('ventas')
            ->where('sede_id', $caja->sede_id)
            ->where('created_at', '>=', $caja->fecha_apertura)
            ->selectRaw("
                SUM(CASE WHEN metodo_pago = 'EFECTIVO_USD' THEN total_usd ELSE 0 END) as efectivo_usd,
                SUM(CASE WHEN metodo_pago = 'EFECTIVO_VES' THEN total_ves ELSE 0 END) as efectivo_ves,
                SUM(CASE WHEN metodo_pago = 'PAGO_MOVIL' THEN total_ves ELSE 0 END) as pago_movil_ves,
                SUM(CASE WHEN metodo_pago = 'PUNTO_DE_VENTA' THEN total_ves ELSE 0 END) as punto_ves,
                SUM(CASE WHEN metodo_pago = 'ZELLE' THEN total_usd ELSE 0 END) as zelle_usd
            ")->first();

        return [
            'efectivo_usd' => (float) ($ventas->efectivo_usd ?? 0) + $caja->monto_inicial_usd,
            'efectivo_ves' => (float) ($ventas->efectivo_ves ?? 0) + $caja->monto_inicial_ves,
            'pago_movil_ves' => (float) ($ventas->pago_movil_ves ?? 0),
            'punto_ves' => (float) ($ventas->punto_ves ?? 0),
            'zelle_usd' => (float) ($ventas->zelle_usd ?? 0),
        ];
    }

    /**
     * Procesar el arqueo final y cerrar la caja.
     */
    public function procesarCierre(CierreCaja $caja, array $declarado, float $tasaBcv, ?string $observaciones = null): CierreCaja
    {
        return DB::transaction(function () use ($caja, $declarado, $tasaBcv, $observaciones) {
            $resumenSistema = $this->calcularResumenCaja($caja);

            // Diferencia en divisas (USD)
            $diffEfectivoUsd = $declarado['efectivo_usd'] - $resumenSistema['efectivo_usd'];
            $diffZelleUsd = $declarado['zelle_usd'] - $resumenSistema['zelle_usd'];

            // Diferencia en moneda nacional convertida a USD según tasa oficial
            $diffEfectivoVesUsd = ($declarado['efectivo_ves'] - $resumenSistema['efectivo_ves']) / $tasaBcv;
            $diffPagoMovilUsd = ($declarado['pago_movil_ves'] - $resumenSistema['pago_movil_ves']) / $tasaBcv;
            $diffPuntoUsd = ($declarado['punto_ves'] - $resumenSistema['punto_ves']) / $tasaBcv;

            $diferenciaTotalUsd = round($diffEfectivoUsd + $diffZelleUsd + $diffEfectivoVesUsd + $diffPagoMovilUsd + $diffPuntoUsd, 2);

            $caja->update([
                'fecha_cierre' => now(),
                'sistema_efectivo_usd' => $resumenSistema['efectivo_usd'],
                'sistema_efectivo_ves' => $resumenSistema['efectivo_ves'],
                'sistema_pago_movil_ves' => $resumenSistema['pago_movil_ves'],
                'sistema_punto_ves' => $resumenSistema['punto_ves'],
                'sistema_zelle_usd' => $resumenSistema['zelle_usd'],
                'declarado_efectivo_usd' => $declarado['efectivo_usd'],
                'declarado_efectivo_ves' => $declarado['efectivo_ves'],
                'declarado_pago_movil_ves' => $declarado['pago_movil_ves'],
                'declarado_punto_ves' => $declarado['punto_ves'],
                'declarado_zelle_usd' => $declarado['zelle_usd'],
                'diferencia_usd' => $diferenciaTotalUsd,
                'estatus' => 'CERRADA',
                'observaciones' => $observaciones,
            ]);

            return $caja->fresh(['cajero', 'sede']);
        });
    }
}