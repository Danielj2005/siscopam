[21/10/2026 17:04] Daniel Barrueta: @extends('layouts.app')

@section('title', 'Conciliación de Pagos')

@section('content')
<div x-data="conciliacionApp()" class="space-y-6">

    <!-- Encabezado de la Sección -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Conciliación de Pagos Móviles</h2>
            <p class="text-sm text-gray-400 mt-1">Verifica referencias bancarias reportadas por los atletas y aprueba la renovación de membresías.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-xs font-semibold rounded-full">
                Tasa Base BCV: {{ number_format(app(\App\Services\BcvService::class)->obtenerTasaUsd(), 2) }} VES
            </span>
        </div>
    </div>

    <!-- Filtros de Estado -->
    <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-80">
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="busqueda" placeholder="Buscar por referencia o cédula..."
                class="w-full pl-10 pr-4 py-2 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <button @click="estatusFiltro = 'PENDIENTE'" 
                :class="estatusFiltro === 'PENDIENTE' ? 'bg-amber-600 text-white' : 'bg-gray-900 text-gray-400 border border-gray-800'"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition flex-1 sm:flex-none">
                Pendientes
            </button>
            <button @click="estatusFiltro = 'APROBADO'" 
                :class="estatusFiltro === 'APROBADO' ? 'bg-emerald-600 text-white' : 'bg-gray-900 text-gray-400 border border-gray-800'"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition flex-1 sm:flex-none">
                Aprobados
            </button>
            <button @click="estatusFiltro = 'RECHAZADO'" 
                :class="estatusFiltro === 'RECHAZADO' ? 'bg-rose-600 text-white' : 'bg-gray-900 text-gray-400 border border-gray-800'"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition flex-1 sm:flex-none">
                Rechazados
            </button>
        </div>
    </div>

    <!-- Tabla de Pagos Reportados -->
    <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-900 text-gray-400 border-b border-gray-800 uppercase tracking-wider">
                    <tr>
                        <th class="p-4">Fecha / Ref</th>
                        <th class="p-4">Atleta</th>
                        <th class="p-4">Método / Banco</th>
                        <th class="p-4">Montos (USD / VES)</th>
                        <th class="p-4">Comprobante</th>
                        <th class="p-4">Estatus</th>
                        <th class="p-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-900 text-gray-300">
                    <!-- Aquí iteraremos la colección que venga del controlador -->
                    @forelse($pagosReportados ?? [] as $pago)
                        <tr class="hover:bg-gray-900/50 transition">
[21/10/2026 17:04] Daniel Barrueta: <td class="p-4">
                                <span class="font-bold text-white block font-mono">Ref: {{ $pago->numero_referencia }}</span>
                                <span class="text-[10px] text-gray-500">{{ $pago->fecha_pago->format('d/m/Y') }}</span>
                            </td>
                            <td class="p-4">
                                <span class="font-semibold text-white block">{{ $pago->cliente->nombre ?? '' }} {{ $pago->cliente->apellido ?? '' }}</span>
                                <span class="text-[10px] text-gray-500">C.I: {{ $pago->cliente->cedula ?? 'N/A' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="text-gray-300 block">{{ $pago->metodo_pago }}</span>
                                <span class="text-[10px] text-gray-500">{{ $pago->banco_origen ?? 'Banco Local' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-emerald-400 block">${{ number_format($pago->monto_usd, 2) }}</span>
                                <span class="text-[10px] text-gray-400">Bs. {{ number_format($pago->monto_ves, 2) }} (Tasa: {{ $pago->tasa_bcv }})</span>
                            </td>
                            <td class="p-4">
                                @if($pago->comprobante_path)
                                    <a href="{{ asset('storage/' . $pago->comprobante_path) }}" target="_blank" class="text-indigo-400 hover:underline font-semibold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Ver Captura
                                    </a>
                                @else
                                    <span class="text-gray-500 text-[11px]">Sin captura</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($pago->estatus === 'PENDIENTE')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">PENDIENTE</span>
                                @elseif($pago->estatus === 'APROBADO')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">APROBADO</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">RECHAZADO</span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-1">
                                @if($pago->estatus === 'PENDIENTE')
                                    <form action="{{ route('admin.pagos.aprobar', $pago->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-semibold transition">Aprobar</button>
                                    </form>
                                    <button @click="abrirRechazo('{{ $pago->id }}')" class="px-2.5 py-1.5 bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30 rounded-lg font-semibold transition">Rechazar</button>
[21/10/2026 17:04] Daniel Barrueta: @else
                                    <span class="text-gray-500 text-[11px]">Procesado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">No hay pagos reportados bajo este criterio.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DE RECHAZO -->
    <div x-show="modalRechazoOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-950/80 backdrop-blur-sm">
        <div @click.away="modalRechazoOpen = false" class="bg-gray-950 border border-gray-800 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h3 class="text-base font-bold text-white">Motivo del Rechazo de Pago</h3>
            <form :action="'/admin/pagos-reportados/' + pagoIdARechazar + '/rechazar'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <textarea name="motivo_rechazo" rows="3" required placeholder="Ej: Referencia no encontrada en cuenta bancaria, monto incorrecto..."
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="button" @click="modalRechazoOpen = false" class="flex-1 py-2.5 bg-gray-800 text-gray-300 text-xs font-semibold rounded-xl">Cancelar</button>
                    <button type="submit" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded-xl">Confirmar Rechazo</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function conciliacionApp() {
        return {
            busqueda: '',
            estatusFiltro: 'PENDIENTE',
            modalRechazoOpen: false,
            pagoIdARechazar: null,

            abrirRechazo(id) {
                this.pagoIdARechazar = id;
                this.modalRechazoOpen = true;
            }
        }
    }
</script>
@endsection