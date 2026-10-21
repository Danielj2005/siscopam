@extends('layouts.app')

@section('title', 'SuperAdmin - Empresas Suscritas')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Organizaciones Suscritas (SaaS)</h2>
            <p class="text-sm text-gray-400 mt-1">Control general de clientes, planes activos y suspensión de servicios.</p>
        </div>
        <a href="{{ route('superadmin.empresas.create') }}" 
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition shadow-lg shadow-indigo-600/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Registrar Empresa
        </a>
    </div>

    <!-- Tabla de Empresas -->
    <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-900 text-gray-400 border-b border-gray-800 uppercase tracking-wider">
                    <tr>
                        <th class="p-4">Empresa / RIF</th>
                        <th class="p-4">Plan SaaS</th>
                        <th class="p-4">Sedes Registradas</th>
                        <th class="p-4">Atletas Activos</th>
                        <th class="p-4">Estatus</th>
                        <th class="p-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-900 text-gray-300">
                    @forelse($empresas ?? [] as $empresa)
                        <tr class="hover:bg-gray-900/50 transition">
                            <td class="p-4">
                                <span class="font-bold text-white block text-sm">{{ $empresa->nombre }}</span>
                                <span class="text-[10px] text-gray-500 font-mono">RIF: {{ $empresa->rif }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $empresa->plan === 'completo' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-gray-800 text-gray-300' }}">
                                    {{ $empresa->plan }}
                                </span>
                            </td>
                            <td class="p-4 font-semibold text-white">
                                {{ $empresa->sedes_count ?? 1 }} Sede(s)
                            </td>
                            <td class="p-4 text-emerald-400 font-bold">
                                {{ $empresa->clientes_count ?? 0 }} Atletas
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $empresa->activo ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                    {{ $empresa->activo ? 'ACTIVO' : 'SUSPENDIDO' }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <form action="{{ route('superadmin.empresas.toggle', $empresa->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg font-semibold text-xs transition {{ $empresa->activo ? 'bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30' : 'bg-emerald-600/20 hover:bg-emerald-600 text-emerald-300 hover:text-white border border-emerald-500/30' }}">
                                        {{ $empresa->activo ? 'Suspender' : 'Activar' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">No hay empresas registradas en la plataforma.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection