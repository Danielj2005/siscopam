<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CierreCaja;
use App\Services\BcvService;
use App\Services\CierreCajaService;
use Illuminate\Http\Request;

class CierreCajaController extends Controller
{
    protected CierreCajaService $cierreCajaService;

    public function __construct(CierreCajaService $cierreCajaService)
    {
        $this->cierreCajaService = $cierreCajaService;
    }

    /**
     * Vista principal / Estado actual de la caja.
     */
    public function index(Request $request)
    {
        $sedeId = $request->user()->sede_id ?? $request->get('sede_id');

        $cajaActiva = CierreCaja::where('sede_id', $sedeId)
            ->where('estatus', 'ABIERTA')
            ->first();

        $resumenSistema = null;
        if ($cajaActiva) {
            $resumenSistema = $this->cierreCajaService->calcularResumenCaja($cajaActiva);
        }

        $historialCierres = CierreCaja::with('cajero')
            ->where('sede_id', $sedeId)
            ->where('estatus', 'CERRADA')
            ->orderBy('fecha_cierre', 'desc')
            ->paginate(10);

        return view('admin.caja.index', compact('cajaActiva', 'resumenSistema', 'historialCierres'));
    }

    /**
     * Abrir Turno de Caja.
     */
    public function aperturar(Request $request)
    {
        $validated = $request->validate([
            'sede_id' => ['required', 'uuid', 'exists:sedes,id'],
            'monto_inicial_usd' => ['required', 'numeric', 'min:0'],
            'monto_inicial_ves' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $this->cierreCajaService->abrirCaja(
                $request->user(),
                $validated['sede_id'],
                (float) $validated['monto_inicial_usd'],
                (float) $validated['monto_inicial_ves']
            );

            return redirect()->back()->with('success', 'Turno de caja aperturado exitosamente.');
        } catch (\DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Realizar Arqueo y Cerrar Caja.
     */
    public function cerrar(Request $request, BcvService $bcvService)
    {
        $validated = $request->validate([
            'caja_id' => ['required', 'uuid', 'exists:cierres_caja,id'],
            'declarado_efectivo_usd' => ['required', 'numeric', 'min:0'],
            'declarado_efectivo_ves' => ['required', 'numeric', 'min:0'],
            'declarado_pago_movil_ves' => ['required', 'numeric', 'min:0'],
            'declarado_punto_ves' => ['required', 'numeric', 'min:0'],
            'declarado_zelle_usd' => ['required', 'numeric', 'min:0'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $caja = CierreCaja::where('empresa_id', $request->user()->empresa_id)
            ->findOrFail($validated['caja_id']);

        if ($caja->estatus !== 'ABIERTA') {
            return redirect()->back()->with('error', 'La caja ya se encuentra cerrada.');
        }

        $tasaBcv = $bcvService->obtenerTasaUsd();

        $cajaCerrada = $this->cierreCajaService->procesarCierre(
            $caja,
            [
                'efectivo_usd' => (float) $validated['declarado_efectivo_usd'],
                'efectivo_ves' => (float) $validated['declarado_efectivo_ves'],
                'pago_movil_ves' => (float) $validated['declarado_pago_movil_ves'],
                'punto_ves' => (float) $validated['declarado_punto_ves'],
                'zelle_usd' => (float) $validated['declarado_zelle_usd'],
            ],
            $tasaBcv,
            $validated['observaciones'] ?? null
        );

        return redirect()->back()->with('success', "Caja cerrada correctamente. Diferencia: {$cajaCerrada->diferencia_usd} USD");
    }
}