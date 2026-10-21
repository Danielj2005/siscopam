@extends('layouts.app')

@section('title', 'Gestión de Atletas')

@section('content')
<div x-data="clientesApp()" class="space-y-6">

    <!-- Encabezado de la Sección -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Atletas y Membresías</h2>
            <p class="text-sm text-gray-400 mt-1">Directorio de miembros, estado de vigencia y renovaciones express.</p>
        </div>
        <div>
            <button @click="abrirModalNuevoAtleta()" 
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition shadow-lg shadow-indigo-600/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Registrar Atleta
            </button>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros -->
    <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:w-96">
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="busqueda" placeholder="Buscar por nombre, apellido o cédula..."
                class="w-full pl-10 pr-4 py-2 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <!-- Filtros por Estado de Membresía -->
        <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
            <button @click="filtroEstado = 'TODOS'" 
                :class="filtroEstado === 'TODOS' ? 'bg-indigo-600 text-white' : 'bg-gray-900 text-gray-400 hover:text-white border border-gray-800'"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap">
                Todos
            </button>
            <button @click="filtroEstado = 'ACTIVA'" 
                :class="filtroEstado === 'ACTIVA' ? 'bg-emerald-600 text-white' : 'bg-gray-900 text-gray-400 hover:text-white border border-gray-800'"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap">
                Activos
            </button>
            <button @click="filtroEstado = 'POR_VENCER'" 
                :class="filtroEstado === 'POR_VENCER' ? 'bg-amber-600 text-white' : 'bg-gray-900 text-gray-400 hover:text-white border border-gray-800'"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap">
                Por Vencer
            </button>
            <button @click="filtroEstado = 'EXPIRADA'" 
                :class="filtroEstado === 'EXPIRADA' ? 'bg-rose-600 text-white' : 'bg-gray-900 text-gray-400 hover:text-white border border-gray-800'"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap">
                Expirados
            </button>
        </div>
    </div>

    <!-- Tabla Principal de Atletas -->
    <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-900/80 text-gray-400 border-b border-gray-800 uppercase tracking-wider">
                    <tr>
                        <th class="p-4">Atleta</th>
                        <th class="p-4">Cédula / RIF</th>
                        <th class="p-4">Contacto</th>
                        <th class="p-4">Vencimiento</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-900 text-gray-300">
                    <template x-for="cliente in atletasFiltrados" :key="cliente.id">
                        <tr class="hover:bg-gray-900/50 transition">
                            <!-- Nombre y Perfil -->
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-600/20 border border-indigo-500/30 text-indigo-400 font-bold flex items-center justify-center text-xs"
                                         x-text="cliente.nombre.charAt(0) + cliente.apellido.charAt(0)"></div>
                                    <div>
                                        <span class="font-semibold text-white block text-sm" x-text="cliente.nombre + ' ' + cliente.apellido"></span>
                                        <span class="text-[10px] text-gray-500" x-text="'Registrado: ' + formatearFecha(cliente.created_at)"></span>
                                    </div>
                                </div>
                            </td>

                            <!-- Cédula -->
                            <td class="p-4 font-mono text-gray-300" x-text="cliente.cedula"></td>

                            <!-- Contacto -->
                            <td class="p-4">
                                <span class="block text-gray-300" x-text="cliente.telefono || 'Sin teléfono'"></span>
                                <span class="block text-[10px] text-gray-500" x-text="cliente.email || 'Sin correo'"></span>
                            </td>

                            <!-- Fecha Vencimiento -->
                            <td class="p-4">
                                <span class="font-semibold" x-text="cliente.fecha_vencimiento ? formatearFecha(cliente.fecha_vencimiento) : 'Sin Plan'"></span>
                                <span class="block text-[10px]" 
                                      :class="obtenerDiasRestantes(cliente.fecha_vencimiento) <= 0 ? 'text-rose-400' : 'text-gray-500'"
                                      x-text="obtenerTextoDias(cliente.fecha_vencimiento)"></span>
                            </td>

                            <!-- Badge Estado -->
                            <td class="p-4">
                                <template x-if="obtenerEstatusCalculado(cliente) === 'ACTIVA'">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span> ACTIVO
                                    </span>
                                </template>
                                <template x-if="obtenerEstatusCalculado(cliente) === 'POR_VENCER'">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5 animate-pulse"></span> POR VENCER
                                    </span>
                                </template>
                                <template x-if="obtenerEstatusCalculado(cliente) === 'EXPIRADA'">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span> EXPIRADO
                                    </span>
                                </template>
                            </td>

                            <!-- Botón Renovación Rápida -->
                            <td class="p-4 text-right">
                                <button @click="abrirModalRenovacion(cliente)" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600/20 hover:bg-indigo-600 text-indigo-300 hover:text-white border border-indigo-500/30 text-xs font-semibold rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Renovar
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="atletasFiltrados.length === 0">
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">No se encontraron atletas registrados con los criterios seleccionados.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DE RENOVACIÓN RÁPIDA DE MEMBRESÍA -->
    <div x-show="modalRenovacionOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-950/80 backdrop-blur-sm">
        <div @click.away="modalRenovacionOpen = false" class="bg-gray-950 border border-gray-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5">
            
            <div class="flex items-center justify-between border-b border-gray-800 pb-3">
                <h3 class="text-base font-bold text-white">Renovación Express de Membresía</h3>
                <button @click="modalRenovacionOpen = false" class="text-gray-400 hover:text-white">&times;</button>
            </div>

            <template x-if="clienteSeleccionado">
                <div class="space-y-4">
                    <!-- Resumen Cliente -->
                    <div class="bg-gray-900/80 p-3 rounded-xl border border-gray-800 flex justify-between items-center">
                        <div>
                            <span class="text-xs font-semibold text-white block" x-text="clienteSeleccionado.nombre + ' ' + clienteSeleccionado.apellido"></span>
                            <span class="text-[10px] text-gray-400" x-text="'C.I.: ' + clienteSeleccionado.cedula"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-gray-400 block">Vencimiento Actual</span>
                            <span class="text-xs font-mono font-bold text-amber-400" x-text="clienteSeleccionado.fecha_vencimiento ? formatearFecha(clienteSeleccionado.fecha_vencimiento) : 'Inactivo'"></span>
                        </div>
                    </div>

                    <form action="{{ route('pagos.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="cliente_id" :value="clienteSeleccionado.id">
                        <input type="hidden" name="tasa_bcv" :value="tasaBcv">

                        <!-- Selección de Plan -->
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Seleccionar Plan / Membresía</label>
                            <select name="plan_id" x-model="planIdSeleccionado" @change="actualizarMontoPlan()" required 
                                class="w-full px-3 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
                                <option value="">-- Selecciona Plan --</option>
                                @foreach($planes ?? [] as $plan)
                                    <option value="{{ $plan->id }}" data-precio="{{ $plan->precio_usd }}" data-dias="{{ $plan->duracion_dias }}">
                                        {{ $plan->nombre }} - ${{ number_format($plan->precio_usd, 2) }} ({{ $plan->duracion_dias }} Días)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Monto Recibido y Moneda -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Monto a Cobrar (USD $)</label>
                                <input type="number" step="0.01" name="monto_recibido" x-model.number="montoUsd" readonly
                                    class="w-full px-3 py-2 bg-gray-900/50 border border-gray-800 rounded-xl text-emerald-400 font-bold text-sm outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Equivalente BCV (VES)</label>
                                <input type="text" :value="'Bs. ' + (montoUsd * tasaBcv).toFixed(2)" readonly
                                    class="w-full px-3 py-2 bg-gray-900/50 border border-gray-800 rounded-xl text-white font-bold text-sm outline-none">
                            </div>
                        </div>

                        <!-- Método de Pago -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">Método de Pago</label>
                                <select name="metodo_pago" required class="w-full px-3 py-2 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
                                    <option value="PAGO_MOVIL">Pago Móvil</option>
                                    <option value="EFECTIVO_USD">Efectivo USD</option>
                                    <option value="EFECTIVO_VES">Efectivo VES</option>
                                    <option value="PUNTO">Punto de Venta</option>
                                    <option value="ZELLE">Zelle</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">N° Referencia</label>
                                <input type="text" name="referencia" placeholder="Ej: 849201"
                                    class="w-full px-3 py-2 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
                            </div>
                        </div>

                        <button type="submit" 
                            class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl transition shadow-lg shadow-emerald-600/30">
                            Procesar Pago y Renovar Membresía
                        </button>
                    </form>
                </div>
            </template>
        </div>
    </div>
    <!-- MODAL DE NUEVO ATLETA -->
    <div x-show="modalNuevoOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-950/80 backdrop-blur-sm">
        <div @click.away="modalNuevoOpen = false" class="bg-gray-950 border border-gray-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-gray-800 pb-3">
                <h3 class="text-base font-bold text-white">Registrar Nuevo Atleta</h3>
                <button @click="modalNuevoOpen = false" class="text-gray-400 hover:text-white">&times;</button>
            </div>

            <form action="{{ route('clientes.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="sede_id" value="{{ auth()->user()->sede_id }}">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Nombre</label>
                        <input type="text" name="nombre" required class="w-full px-3 py-2 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Apellido</label>
                        <input type="text" name="apellido" required class="w-full px-3 py-2 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Cédula</label>
                        <input type="text" name="cedula" required placeholder="V-12345678" class="w-full px-3 py-2 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Teléfono</label>
                        <input type="text" name="telefono" placeholder="0412-1234567" class="w-full px-3 py-2 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Correo Electrónico</label>
                    <input type="email" name="email" placeholder="atleta@email.com" class="w-full px-3 py-2 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl transition shadow-lg shadow-indigo-600/30">
                    Guardar y Registrar Atleta
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function clientesApp() {
        return {
            busqueda: '',
            filtroEstado: 'TODOS',
            tasaBcv: {{ app(\App\Services\BcvService::class)->obtenerTasaUsd() }},
            clientes: @json($clientes ?? []),
            modalRenovacionOpen: false,
            clienteSeleccionado: null,
            planIdSeleccionado: '',
            montoUsd: 0,

            get atletasFiltrados() {
                return this.clientes.filter(c => {
                    let coincideBusqueda = (c.nombre + ' ' + c.apellido + ' ' + c.cedula).toLowerCase().includes(this.busqueda.toLowerCase());
                    let estatusCalculado = this.obtenerEstatusCalculado(c);

                    if (this.filtroEstado === 'TODOS') return coincideBusqueda;
                    return coincideBusqueda && estatusCalculado === this.filtroEstado;
                });
            },

            obtenerEstatusCalculado(c) {
                if (!c.fecha_vencimiento) return 'EXPIRADA';
                let dias = this.obtenerDiasRestantes(c.fecha_vencimiento);
                if (dias < 0) return 'EXPIRADA';
                if (dias <= 3) return 'POR_VENCER';
                return 'ACTIVA';
            },

            obtenerDiasRestantes(fechaStr) {
                if (!fechaStr) return -1;
                let hoy = new Date();
                hoy.setHours(0,0,0,0);
                let vencimiento = new Date(fechaStr);
                vencimiento.setHours(0,0,0,0);
                let diffTime = vencimiento - hoy;
                return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            },

            obtenerTextoDias(fechaStr) {
                let dias = this.obtenerDiasRestantes(fechaStr);
                if (dias < 0) return 'Membresía Vencida';
                if (dias === 0) return 'Vence Hoy';
                return 'Quedan ' + dias + ' días';
            },

            formatearFecha(fechaStr) {
                if (!fechaStr) return 'N/A';
                let d = new Date(fechaStr);
                return d.toLocaleDateString('es-VE', { day: '2-digit', month: '2-digit', year: 'numeric' });
            },

            abrirModalRenovacion(cliente) {
                this.clienteSeleccionado = cliente;
                this.modalRenovacionOpen = true;
            },

            actualizarMontoPlan() {
                let select = document.querySelector('select[name="plan_id"]');
                let option = select.options[select.selectedIndex];
                this.montoUsd = parseFloat(option.getAttribute('data-precio') || 0);
            },

            abrirModalNuevoAtleta() {
                this.modalNuevoOpen = true;
            }
        }
    }
</script>
@endsection