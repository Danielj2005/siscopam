<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Sede;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmpresaController extends Controller
{
    /**
     * Almacenar nueva empresa, sede inicial y usuario administrador principal.
     */
    public function store(Request $request)
    {
        // Validar datos de entrada corporativos y del admin
        $validated = $request->validate([
            'empresa_nombre' => ['required', 'string', 'max:150', 'unique:empresas,nombre'],
            'rif' => ['required', 'string', 'max:30', 'unique:empresas,rif'],
            'plan' => ['required', Rule::in(['basico', 'completo'])],
            
            'sede_nombre' => ['required', 'string', 'max:150'],
            'sede_direccion' => ['required', 'string', 'max:255'],
            
            'admin_nombre' => ['required', 'string', 'max:100'],
            'admin_email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8'],
        ]);

        return DB::transaction(function () use ($validated) {
            // 1. Crear la Empresa SaaS
            $empresa = Empresa::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'nombre' => $validated['empresa_nombre'],
                'rif' => $validated['rif'],
                'plan' => $validated['plan'],
                'activo' => true,
            ]);

            // 2. Crear la Sede Principal
            $sede = Sede::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'empresa_id' => $empresa->id,
                'nombre' => $validated['sede_nombre'],
                'direccion' => $validated['sede_direccion'],
            ]);

            // 3. Asegurar o crear el Rol de Administrador para esta empresa
            $rolAdmin = Role::firstOrCreate(
                ['empresa_id' => $empresa->id, 'nombre' => 'ADMIN_EMPRESA'],
                [
                    'id' => \Illuminate\Support\Str::uuid(),
                    'permisos_json' => ['all' => true] // Acceso total para el admin del gimnasio
                ]
            );

            // 4. Crear el Usuario Administrador de la Empresa
            User::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'empresa_id' => $empresa->id,
                'sede_id' => $sede->id,
                'role_id' => $rolAdmin->id,
                'name' => $validated['admin_nombre'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
            ]);

            return redirect()->back()->with('success', 'Empresa, sede y usuario administrador creados exitosamente.');
        });
    }
    /**
     * Listar todas las empresas suscritas al SaaS.
     */
    public function index()
    {
        $empresas = Empresa::withCount(['sedes', 'clientes'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('superadmin.empresas.index', compact('empresas'));
    }

    /**
     * Suspender o reactivar una empresa SaaS.
     */
    public function toggle(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update(['activo' => ! $empresa->activo]);

        return redirect()->back()->with('success', "Estatus de la empresa actualizado correctamente.");
    }
}