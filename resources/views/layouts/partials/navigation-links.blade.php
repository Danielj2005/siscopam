@php
    $routeName = request()->route()->getName();
@endphp

<!-- Sección Operativa -->
<div class="px-3 pb-2 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Operaciones</div>

<a href="{{ route('checkin.index') }}" 
   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ str_contains($routeName, 'checkin') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/60' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Check-in / Acceso
</a>

<a href="{{ route('pos.index') }}" 
   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ str_contains($routeName, 'pos') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/60' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    Punto de Venta (POS)
</a>

<a href="{{ route('caja.index') }}" 
   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ str_contains($routeName, 'caja') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/60' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    Arqueo / Cierre Caja
</a>

<!-- Sección Gestión -->
<div class="px-3 pt-4 pb-2 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Administración</div>

<a href="{{ route('pagos.conciliacion') }}" 
   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ str_contains($routeName, 'pagos') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/60' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    Conciliar Pagos
</a>

<a href="{{ route('clientes.index') }}" 
   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ str_contains($routeName, 'clientes') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/60' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    Atletas / Clientes
</a>