@extends('layouts.app')

@section('title', 'Arqueo y Cierre de Caja')

@section('content')
<div x-data="arqueoCajaApp()" class="space-y-8">
    <!-- Encabezado de la Sección -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Control y Arqueo de Caja</h2>
            <p class="text-sm text-gray-400 mt-1">Apertura, conciliación de valores en efectivo/digitales y cierres de turno por sede.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $cajaActiva ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                <span class="w-2 h-2 rounded-full {{ $cajaActiva ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }} mr-2"></span>
                {{ $cajaActiva ? 'Caja Abierta' : 'Caja Cerrada' }}
            </span>
        </div>
    </div>

    @if(!$cajaActiva)
        <!-- Formulario de Apertura de Caja -->
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 shadow-xl max-w-2xl">
            <h3 class="text-lg font-semibold text-white mb-2">Aperturar Turno de Caja</h3>
            <p class="text-xs text-gray-400 mb-6">Ingresa el fondo inicial asignado a la caja registradora en ambas monedas.</p>

            <form action="{{ route('caja.aperturar') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="sede_id" value="{{ auth()->user()->sede_id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-2">Fondo Inicial (USD $)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                            <input type="number" step="0.01" min="0" name="monto_inicial_usd" value="0.00" required
                                class="w-full pl-8 pr-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-2">Fondo Inicial (VES Bs)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Bs</span>
                            <input type="number" step="0.01" min="0" name="monto_inicial_ves" value="0.00" required
                                class="w-full pl-9 pr-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-sm transition shadow-lg shadow-indigo-600/30">
                    Aperturar Caja Ahora
                </button>
            </form>
        </div>
    @else
        <!-- Panel de Control de Caja Activa -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Totales en Sistema (Lectura) -->
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Totales en Sistema</h3>
                    
                    <div class="space-y-4">
                        <div class="p-3 bg-gray-900/60 rounded-xl border border-gray-800/80">
                            <span class="text-xs text-gray-400 block">Efectivo USD</span>
                            <span class="text-xl font-bold text-emerald-400">${{ number_format($resumenSistema['efectivo_usd'] ?? 0, 2) }}</span>
                        </div>

                        <div class="p-3 bg-gray-900/60 rounded-xl border border-gray-800/80">
                            <span class="text-xs text-gray-400 block">Efectivo VES</span>
                            <span class="text-xl font-bold text-emerald-400">Bs. {{ number_format($resumenSistema['efectivo_ves'] ?? 0, 2) }}</span>
                        </div>

                        <div class="p-3 bg-gray-900/60 rounded-xl border border-gray-800/80">
                            <span class="text-xs text-gray-400 block">Pago Móvil VES</span>
                            <span class="text-xl font-bold text-white">Bs. {{ number_format($resumenSistema['pago_movil_ves'] ?? 0, 2) }}</span>
                        </div>

                        <div class="p-3 bg-gray-900/60 rounded-xl border border-gray-800/80">
                            <span class="text-xs text-gray-400 block">Punto de Venta VES</span>
                            <span class="text-xl font-bold text-white">Bs. {{ number_format($resumenSistema['punto_ves'] ?? 0, 2) }}</span>
                        </div>

                        <div class="p-3 bg-gray-900/60 rounded-xl border border-gray-800/80">
                            <span class="text-xs text-gray-400 block">Zelle USD</span>
                            <span class="text-xl font-bold text-white">${{ number_format($resumenSistema['zelle_usd'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Conteo Físico / Arqueo -->
            <div class="lg:col-span-2">
                <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-white mb-2">Formulario de Arqueo y Conteo Físico</h3>
                    <p class="text-xs text-gray-400 mb-6">Ingresa los montos físicos contados en gaveta o cuentas bancarias para calcular descuadres.</p>

                    <form action="{{ route('caja.cerrar') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="caja_id" value="{{ $cajaActiva->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Declarado Efectivo USD ($)</label>
                                <input type="number" step="0.01" min="0" x-model.number="declarado.efectivo_usd" name="declarado_efectivo_usd" required
                                    class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Declarado Efectivo VES (Bs)</label>
                                <input type="number" step="0.01" min="0" x-model.number="declarado.efectivo_ves" name="declarado_efectivo_ves" required
                                    class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Declarado Pago Móvil (Bs)</label>
                                <input type="number" step="0.01" min="0" x-model.number="declarado.pago_movil_ves" name="declarado_pago_movil_ves" required
                                    class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Declarado Punto de Venta (Bs)</label>
                                <input type="number" step="0.01" min="0" x-model.number="declarado.punto_ves" name="declarado_punto_ves" required
                                    class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-300 mb-1">Declarado Zelle ($)</label>
                                <input type="number" step="0.01" min="0" x-model.number="declarado.zelle_usd" name="declarado_zelle_usd" required
                                    class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Observaciones o Notas de Cierre</label>
                            <textarea name="observaciones" rows="3" placeholder="Detalles de billetes en mal estado, faltantes justificados, etc."
                                class="w-full px-4 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                        </div>

                        <!-- Indicador de Diferencia Dinámica -->
                        <div class="p-4 rounded-xl border flex items-center justify-between"
                             :class="calcularDiferenciaTotal() >= 0 ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'">
                            <div>
                                <span class="text-xs font-semibold uppercase block">Diferencia Estimada</span>
                                <span class="text-xs opacity-80">Sobrante o faltante global convertido a USD</span>
                            </div>
                            <span class="text-xl font-bold" x-text="(calcularDiferenciaTotal() >= 0 ? '+' : '') + calcularDiferenciaTotal().toFixed(2) + ' USD'"></span>
                        </div>

                        <button type="submit" class="w-full py-3 bg-rose-600 hover:bg-rose-500 text-white font-semibold rounded-xl text-sm transition shadow-lg shadow-rose-600/30">
                            Cerrar y Realizar Arqueo de Caja
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Historial de Cierres Anteriores -->
    <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 shadow-xl">
        <h3 class="text-base font-semibold text-white mb-4">Historial Reciente de Cierres de Caja</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-900 text-gray-400 border-b border-gray-800">
                    <tr>
                        <th class="p-3">Apertura</th>
                        <th class="p-3">Cierre</th>
                        <th class="p-3">Cajero</th>
                        <th class="p-3">Decl. USD</th>
                        <th class="p-3">Decl. VES</th>
                        <th class="p-3">Diferencia</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-900 text-gray-300">
                    @forelse($historialCierres as $cierre)
                        <tr class="hover:bg-gray-900/50 transition">
                            <td class="p-3">{{ $cierre->fecha_apertura->format('d/m/Y h:i A') }}</td>
                            <td class="p-3">{{ $cierre->fecha_cierre ? $cierre->fecha_cierre->format('d/m/Y h:i A') : 'N/A' }}</td>
                            <td class="p-3 font-medium text-white">{{ $cierre->cajero->name ?? 'Sistema' }}</td>
                            <td class="p-3">${{ number_format($cierre->declarado_efectivo_usd, 2) }}</td>
                            <td class="p-3">Bs. {{ number_format($cierre->declarado_efectivo_ves, 2) }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $cierre->diferencia_usd >= 0 ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                    {{ $cierre->diferencia_usd >= 0 ? '+' : '' }}{{ number_format($cierre->diferencia_usd, 2) }} USD
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">No hay registros de cierres anteriores en esta sede.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $historialCierres->links() }}
        </div>
    </div>
</div>

<script>
    function arqueoCajaApp() {
        return {
            tasaBcv: {{ app(\App\Services\BcvService::class)->obtenerTasaUsd() }},
            sistema: {
                efectivo_usd: {{ $resumenSistema['efectivo_usd'] ?? 0 }},
                efectivo_ves: {{ $resumenSistema['efectivo_ves'] ?? 0 }},
                pago_movil_ves: {{ $resumenSistema['pago_movil_ves'] ?? 0 }},
                punto_ves: {{ $resumenSistema['punto_ves'] ?? 0 }},
                zelle_usd: {{ $resumenSistema['zelle_usd'] ?? 0 }}
            },
            declarado: {
                efectivo_usd: 0,
                efectivo_ves: 0,
                pago_movil_ves: 0,
                punto_ves: 0,
                zelle_usd: 0
            },
            calcularDiferenciaTotal() {
                let diffUsd = (this.declarado.efectivo_usd - this.sistema.efectivo_usd) + 
                              (this.declarado.zelle_usd - this.sistema.zelle_usd);
                
                let diffVesEnUsd = ((this.declarado.efectivo_ves - this.sistema.efectivo_ves) + 
                                   (this.declarado.pago_movil_ves - this.sistema.pago_movil_ves) + 
                                   (this.declarado.punto_ves - this.sistema.punto_ves)) / (this.tasaBcv || 1);
                
                return diffUsd + diffVesEnUsd;
            }
        }
    }
</script>
@endsection