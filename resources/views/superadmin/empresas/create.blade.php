@extends('layouts.app')

@section('title', 'SuperAdmin - Nueva Empresa')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between border-b border-gray-800 pb-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Registro de Nueva Empresa SaaS</h2>
            <p class="text-sm text-gray-400 mt-1">Crea una nueva organización, su sede principal y el usuario administrador inicial.</p>
        </div>
        <a href="{{ route('superadmin.empresas.index') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs font-semibold rounded-xl transition border border-gray-700">
            &larr; Volver al Listado
        </a>
    </div>

    <div class="bg-gray-950 border border-gray-800 rounded-3xl p-8 shadow-2xl">
        <form action="{{ route('superadmin.empresas.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- SECCIÓN 1: DATOS DE LA EMPRESA -->
            <div>
                <h3 class="text-sm font-bold text-indigo-400 uppercase tracking-wider mb-4">1. Datos Corporativos</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-300 mb-1">Nombre del Gimnasio / Empresa</label>
                        <input type="text" name="empresa_nombre" value="{{ old('empresa_nombre') }}" required placeholder="Ej: Gold's Gym Venezuela"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">RIF / Identificación Fiscal</label>
                        <input type="text" name="rif" value="{{ old('rif') }}" required placeholder="J-12345678-0"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-medium text-gray-300 mb-1">Plan Contratado (SaaS)</label>
                        <select name="plan" required class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="basico">Plan Básico (Control de Pagos, Membresías y Check-in)</option>
                            <option value="completo" selected>Plan Completo (Acceso Total: POS, Inventario, Arqueo, Reportes y PWA)</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="border-gray-800">

            <!-- SECCIÓN 2: SEDE PRINCIPAL -->
            <div>
                <h3 class="text-sm font-bold text-indigo-400 uppercase tracking-wider mb-4">2. Sede Principal</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Nombre de la Sede</label>
                        <input type="text" name="sede_nombre" value="{{ old('sede_nombre') }}" required placeholder="Ej: Sede Centro Barquisimeto"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Dirección Física</label>
                        <input type="text" name="sede_direccion" value="{{ old('sede_direccion') }}" required placeholder="Av. Principal, Edificio X"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
            </div>

            <hr class="border-gray-800">

            <!-- SECCIÓN 3: USUARIO ADMINISTRADOR -->
            <div>
                <h3 class="text-sm font-bold text-indigo-400 uppercase tracking-wider mb-4">3. Usuario Administrador del Gimnasio</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Nombre del Administrador</label>
                        <input type="text" name="admin_nombre" value="{{ old('admin_nombre') }}" required placeholder="Carlos Pérez"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Correo Electrónico (Acceso)</label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" required placeholder="admin@gimnasio.com"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Contraseña Temporal</label>
                        <input type="password" name="admin_password" required placeholder="Mínimo 8 caracteres"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-xl transition shadow-xl shadow-indigo-600/30">
                    Registrar Organización y Habilitar Sistema
                </button>
            </div>
        </form>
    </div>
</div>
@endsection