<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetalleVenta;
use App\Models\InventarioSede;
use App\Models\Producto;
use App\Models\Venta;
use App\Services\BcvService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PosController extends Controller
{
    /**
     * Procesar una venta en el POS y descontar stock automáticamente por sede.
     */
    public function procesarVenta(Request $request, BcvService $bcvService)
    {
        $validated = $request->validate([
            'sede_id' => ['required', 'uuid', 'exists:sedes,id'],
            'cliente_id' => ['nullable', 'uuid', 'exists:clientes,id'],
            'moneda_pago' => ['required', Rule::in(['USD', 'VES'])],
            'metodo_pago' => ['required', Rule::in(['PAGO_MOVIL', 'EFECTIVO_USD', 'EFECTIVO_VES', 'PUNTO', 'ZELLE'])],
            'referencia' => ['nullable', 'string', 'max:50'],
            'productos' => ['required', 'array', 'min:1'],
            'productos.*.id' => ['required', 'uuid', 'exists:productos,id'],
            'productos.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $user = $request->user();
        $tasaBcv = $bcvService->obtenerTasaUsd();

        return DB::transaction(function () use ($validated, $user, $tasaBcv) {
            $totalUsd = 0;
            $itemsAProcesar = [];

            // 1. Validar Stock por Sede y Calcular Totales
            foreach ($validated['productos'] as $item) {
                $producto = Producto::findOrFail($item['id']);

                // Bloqueo pesimista para evitar inconsistencias con ventas concurrentes
                $inventario = InventarioSede::where('sede_id', $validated['sede_id'])
                    ->where('producto_id', $producto->id)
                    ->lockForUpdate()
                    ->first();

                if (! $inventario || $inventario->stock_actual < $item['cantidad']) {
                    $disponible = $inventario->stock_actual ?? 0;
                    throw new \Exception("Stock insuficiente para '{$producto->nombre}'. Disponible: {$disponible}, Solicitado: {$item['cantidad']}");
                }

                $precioUnitarioUsd = (float) $producto->precio_base_usd;
                $subtotalUsd = $precioUnitarioUsd * $item['cantidad'];
                $subtotalVes = round($subtotalUsd * $tasaBcv, 2);

                $totalUsd += $subtotalUsd;

                $itemsAProcesar[] = [
                    'producto' => $producto,
                    'inventario' => $inventario,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario_usd' => $precioUnitarioUsd,
                    'subtotal_usd' => $subtotalUsd,
                    'subtotal_ves' => $subtotalVes,
                ];
            }

            $totalVes = round($totalUsd * $tasaBcv, 2);

            // 2. Crear Registro Cabecera de Venta
            $venta = Venta::create([
                'empresa_id' => $user->empresa_id,
                'sede_id' => $validated['sede_id'],
                'usuario_id' => $user->id,
                'cliente_id' => $validated['cliente_id'] ?? null,
                'total_usd' => $totalUsd,
                'total_ves' => $totalVes,
                'tasa_bcv_usada' => $tasaBcv,
                'moneda_pago' => $validated['moneda_pago'],
                'metodo_pago' => $validated['metodo_pago'],
                'referencia' => $validated['referencia'] ?? null,
            ]);

            // 3. Crear Detalle y Descontar Stock
            foreach ($itemsAProcesar as $item) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $item['producto']->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario_usd' => $item['precio_unitario_usd'],
                    'subtotal_usd' => $item['subtotal_usd'],
                    'subtotal_ves' => $item['subtotal_ves'],
                ]);

                // Descuento de stock en la sede activa
                $item['inventario']->decrement('stock_actual', $item['cantidad']);
            }

            return response()->json([
                'message' => 'Venta registrada e inventario actualizado exitosamente.',
                'venta' => $venta->load('detalles.producto'),
            ], 201);
        });
    }

    /**
     * Consultar catálogo de productos con el stock disponible en la sede actual.
     */
    public function catalogoSede(Request $request, string $sedeId, BcvService $bcvService)
    {
        $tasaBcv = $bcvService->obtenerTasaUsd();

        $productos = DB::table('productos')
            ->join('inventario_sedes', 'productos.id', '=', 'inventario_sedes.producto_id')
            ->where('inventario_sedes.sede_id', $sedeId)
            ->select(
                'productos.id',
                'productos.codigo_barra',
                'productos.nombre',
                'productos.precio_base_usd',
                'inventario_sedes.stock_actual',
                'inventario_sedes.stock_minimo'
            )
            ->get()
            ->map(function ($item) use ($tasaBcv) {
                $item->precio_ves = round($item->precio_base_usd * $tasaBcv, 2);
                return $item;
            });

        return response()->json([
            'tasa_bcv' => $tasaBcv,
            'productos' => $productos,
        ]);
    }

    /**
     * Generar datos del ticket de venta para la impresora térmica o vista de impresión.
     */
    public function obtenerTicket(string $ventaId)
    {
        $venta = Venta::with(['empresa', 'sede', 'vendedor', 'cliente', 'detalles.producto'])
            ->findOrFail($ventaId);

        $ticketData = [
            'empresa' => [
                'nombre' => $venta->empresa->nombre,
                'rif' => $venta->empresa->rif,
            ],
            'sede' => [
                'nombre' => $venta->sede->nombre,
                'direccion' => $venta->sede->direccion,
            ],
            'comprobante' => [
                'numero_ticket' => 'TICK-' . strtoupper(substr($venta->id, 0, 8)),
                'fecha_hora' => $venta->created_at->format('d/m/Y h:i A'),
                'vendedor' => $venta->vendedor->name,
                'cliente' => $venta->cliente ? "{$venta->cliente->nombre} {$venta->cliente->apellido}" : 'CLIENTE CONTADO',
                'cedula_cliente' => $venta->cliente->cedula ?? 'N/A',
                'metodo_pago' => $venta->metodo_pago,
                'referencia' => $venta->referencia ?? 'N/A',
            ],
            'totales' => [
                'total_usd' => number_format($venta->total_usd, 2, '.', ''),
                'total_ves' => number_format($venta->total_ves, 2, '.', ''),
                'tasa_bcv' => number_format($venta->tasa_bcv_usada, 4, '.', ''),
            ],
            'items' => $venta->detalles->map(function ($det) use ($venta) {
                $subtotalVes = round($det->subtotal_usd * $venta->tasa_bcv_usada, 2);
                return [
                    'producto' => $det->producto->nombre,
                    'cantidad' => $det->cantidad,
                    'precio_usd' => number_format($det->precio_unitario_usd, 2, '.', ''),
                    'subtotal_usd' => number_format($det->subtotal_usd, 2, '.', ''),
                    'subtotal_ves' => number_format($subtotalVes, 2, '.', ''),
                ];
            }),
        ];

        return response()->json($ticketData);
    }
}