<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Producto;
use \App\Models\InventarioSede;
class ProductoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'codigo_barras' => ['nullable', 'string', 'max:50'],
            'precio_usd' => ['required', 'numeric', 'min:0.01'],
            'stock_inicial' => ['required', 'integer', 'min:0'],
            'sede_id' => ['required', 'uuid', 'exists:sedes,id'],
        ]);

        $producto = Producto::create([
            'empresa_id' => $request->user()->empresa_id,
            'nombre' => $validated['nombre'],
            'codigo_barras' => $validated['codigo_barras'] ?? null,
            'precio_usd' => $validated['precio_usd'],
            'activo' => true,
        ]);

        InventarioSede::create([
            'sede_id' => $validated['sede_id'],
            'producto_id' => $producto->id,
            'stock_actual' => $validated['stock_inicial'],
        ]);

        return redirect()->back()->with('success', 'Producto e inventario registrado con éxito.');
    }
}
