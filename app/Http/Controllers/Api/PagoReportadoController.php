<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\PagoReportado;
use App\Services\BcvService;
use App\Services\PagoConciliacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PagoReportadoController extends Controller
{
    protected PagoConciliacionService $conciliacionService;

    public function __construct(PagoConciliacionService $conciliacionService)
    {
        $this->conciliacionService = $conciliacionService;
    }

    /**
     * CLIENTE: Reportar un nuevo Pago Móvil o Transferencia.
     */
    public function reportarPagoCliente(Request $request, BcvService $bcvService)
    {
        $cliente = $request->user()->cliente;

        if (! $cliente) {
            return response()->json(['error' => 'Usuario no vinculado a ningún perfil de cliente.'], 404);
        }

        $validated = $request->validate([
            'sede_id' => ['required', 'uuid', 'exists:sedes,id'],
            'monto_usd' => ['required', 'numeric', 'min:1'],
            'metodo_pago' => ['required', Rule::in(['PAGO_MOVIL', 'TRANSFERENCIA', 'EFECTIVO_USD', 'ZELLE'])],
            'banco_origen' => ['nullable', 'string', 'max:100'],
            'banco_destino' => ['nullable', 'string', 'max:100'],
            'numero_referencia' => ['required', 'string', 'max:50'],
            'fecha_pago' => ['required', 'date', 'before_or_equal:today'],
            'comprobante' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'dias_renovacion' => ['nullable', 'integer', 'min:1'],
        ]);

        $tasaBcv = $bcvService->obtenerTasaUsd();
        $montoVes = round($validated['monto_usd'] * $tasaBcv, 2);

        $comprobantePath = null;
        if ($request->hasFile('comprobante')) {
            $comprobantePath = $request->file('comprobante')->store('comprobantes', 'public');
        }

        $pago = PagoReportado::create([
            'empresa_id' => $cliente->empresa_id,
            'sede_id' => $validated['sede_id'],
            'cliente_id' => $cliente->id,
            'monto_usd' => $validated['monto_usd'],
            'monto_ves' => $montoVes,
            'tasa_bcv' => $tasaBcv,
            'metodo_pago' => $validated['metodo_pago'],
            'banco_origen' => $validated['banco_origen'] ?? null,
            'banco_destino' => $validated['banco_destino'] ?? null,
            'numero_referencia' => $validated['numero_referencia'],
            'comprobante_path' => $comprobantePath,
            'fecha_pago' => $validated['fecha_pago'],
            'dias_renovacion' => $validated['dias_renovacion'] ?? 30,
            'estatus' => 'PENDIENTE',
        ]);

        return response()->json([
            'message' => 'Pago reportado exitosamente. Pendiente de verificación por recepción.',
            'pago' => $pago,
        ], 201);
    }

    /**
     * CLIENTE: Consultar el historial de pagos reportados propios.
     */
    public function misPagos(Request $request)
    {
        $cliente = $request->user()->cliente;

        if (! $cliente) {
            return response()->json(['error' => 'No hay cliente asociado'], 404);
        }

        $pagos = PagoReportado::where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($pagos);
    }

    /**
     * ADMIN/RECEPCIÓN: Listar pagos reportados para conciliación con filtros.
     */
    public function index(Request $request)
    {
        $query = PagoReportado::with(['cliente:id,nombre,apellido,cedula,telefono', 'sede:id,nombre'])
            ->where('empresa_id', $request->user()->empresa_id);

        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        if ($request->filled('buscar')) {
            $busqueda = $request->buscar;
            $query->where(function ($q) use ($busqueda) {
                $q->where('numero_referencia', 'like', "%{$busqueda}%")
                    ->orWhereHas('cliente', function ($qc) use ($busqueda) {
                        $qc->where('cedula', 'like', "%{$busqueda}%")
                            ->orWhere('nombre', 'like', "%{$busqueda}%")
                            ->orWhere('apellido', 'like', "%{$busqueda}%");
                        });
                });
        }

        $pagos = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($pagos);
    }

    /**
     * ADMIN/RECEPCIÓN: Aprobar un pago y renovar suscripción del cliente.
     */
    public function aprobar(Request $request, string $id)
    {
        $pago = PagoReportado::where('empresa_id', $request->user()->empresa_id)
            ->findOrFail($id);

        try {
            $pagoAprobado = $this->conciliacionService->aprobarPago($pago, $request->user());

            return response()->json([
                'message' => 'Pago aprobado correctamente. Membresía del cliente renovada.',
                'pago' => $pagoAprobado,
                'nueva_fecha_vencimiento' => $pagoAprobado->cliente->fecha_vencimiento->format('d/m/Y'),
            ]);
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * ADMIN/RECEPCIÓN: Rechazar un pago reportado.
     */
    public function rechazar(Request $request, string $id)
    {
        $validated = $request->validate([
            'motivo_rechazo' => ['required', 'string', 'max:255'],
        ]);

        $pago = PagoReportado::where('empresa_id', $request->user()->empresa_id)
            ->findOrFail($id);

        try {
            $pagoRechazado = $this->conciliacionService->rechazarPago(
                $pago,
                $request->user(),
                $validated['motivo_rechazo']
            );

            return response()->json([
                'message' => 'El pago ha sido rechazado.',
                'pago' => $pagoRechazado,
            ]);
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}