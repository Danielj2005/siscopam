<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Registrar un nuevo atleta/cliente desde el panel administrativo.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'cedula' => ['required', 'string', 'max:20', 'unique:clientes,cedula'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'sede_id' => ['required', 'uuid', 'exists:sedes,id'],
        ]);

        $user = $request->user();

        Cliente::create([
            'empresa_id' => $user->empresa_id,
            'sede_id' => $validated['sede_id'],
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'cedula' => $validated['cedula'],
            'telefono' => $validated['telefono'] ?? null,
            'email' => $validated['email'] ?? null,
            'activo' => true,
            'fecha_vencimiento' => null, // Se asignará al registrar su primer pago/plan
        ]);

        return redirect()->back()->with('success', 'Atleta registrado exitosamente.');
    }
}