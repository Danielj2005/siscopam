@extends('layouts.app')

@section('title', 'Check-in y Control de Acceso')

@section('content')
<div x-data="checkinApp()" x-init="iniciarLector()" class="space-y-6">

    <!-- Encabezado de la Sección -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Control de Acceso / Torniquete</h2>
            <p class="text-sm text-gray-400 mt-1">Escáner de código QR dinámico o verificación manual por cédula de identidad.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="modoManual = !modoManual" 
                class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs font-semibold rounded-xl transition border border-gray-700">
                <span x-text="modoManual ? 'Usar Escáner QR' : 'Ingreso Manual por Cédula'"></span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Lector de Cámara o Formulario Manual (Izquierda) -->
        <div class="lg:col-span-5 bg-gray-950 border border-gray-800 rounded-2xl p-6 shadow-2xl flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Punto de Validación
                </h3>

                <!-- Contenedor del Escáner QR de Cámara -->
                <div x-show="!modoManual" class="relative">
                    <div id="reader" class="w-full bg-gray-900 rounded-xl overflow-hidden border border-gray-800"></div>
                    <p class="text-[11px] text-gray-500 text-center mt-3">Apunta el código QR de la App del cliente hacia la cámara.</p>
                </div>

                <!-- Formulario Manual por Cédula -->
                <div x-show="modoManual" class="space-y-4" style="display: none;">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-2">Cédula de Identidad del Atleta</label>
                        <div class="relative">
                            <input type="text" x-model="cedulaManual" @keydown.enter="procesarManual()" placeholder="Ej: V-18293041"
                                class="w-full pl-4 pr-10 py-3 bg-gray-900 border border-gray-800 rounded-xl text-white font-mono text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                    </div>
                    <button @click="procesarManual()" :disabled="!cedulaManual"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 disabled:bg-gray-800 text-white font-bold rounded-xl text-sm transition shadow-lg shadow-indigo-600/30">
                        Validar Acceso Manual
                    </button>
                </div>
            </div>

            <!-- Tasa y Sede Activa -->
            <div class="mt-6 pt-4 border-t border-gray-900 flex justify-between text-[11px] text-gray-500">
                <span>Sede: <strong class="text-gray-300">{{ auth()->user()->sede->nombre ?? 'Principal' }}</strong></span>
                <span>Tasa: <strong class="text-emerald-400">{{ number_format(app(\App\Services\BcvService::class)->obtenerTasaUsd(), 2) }} VES</strong></span>
            </div>
        </div>

        <!-- Feedback Visual de Acceso (Derecha) -->
        <div class="lg:col-span-7 flex flex-col">
            
            <!-- Estado Standby / Espera -->
            <template x-if="!ultimoResultado">
                <div class="bg-gray-950 border border-gray-800 rounded-2xl p-12 text-center flex flex-col items-center justify-center h-full min-h-[350px]">

                    <div class="w-20 h-20 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-600 mb-4 animate-pulse">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-300">Esperando Lectura de Acceso</h4>
                    <p class="text-xs text-gray-500 mt-1 max-w-sm">Escanea el código QR o ingresa la cédula para validar el estado de la membresía en tiempo real.</p>
                </div>
            </template>

            <!-- Estado ACCESO PERMITIDO (Pantalla Verde) -->
            <template x-if="ultimoResultado && ultimoResultado.acceso">
                <div class="bg-emerald-950/40 border-2 border-emerald-500 rounded-2xl p-8 text-center flex flex-col items-center justify-center h-full min-h-[350px] shadow-2xl shadow-emerald-950/50">
                    <div class="w-24 h-24 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center mb-4">
                        <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold rounded-full uppercase tracking-widest mb-2">Acceso Autorizado</span>
                    <h3 class="text-3xl font-extrabold text-white" x-text="ultimoResultado.asistencia.cliente.nombre + ' ' + ultimoResultado.asistencia.cliente.apellido"></h3>
                    <p class="text-sm font-mono text-emerald-300 mt-1" x-text="'C.I.: ' + ultimoResultado.asistencia.cliente.cedula"></p>
                    
                    <div class="mt-6 pt-4 border-t border-emerald-900/60 w-full max-w-xs flex justify-between text-xs text-emerald-200">
                        <span>Estado Membresía:</span>
                        <strong class="text-white">ACTIVA</strong>
                    </div>
                </div>
            </template>

            <!-- Estado ACCESO DENEGADO (Pantalla Roja) -->
            <template x-if="ultimoResultado && !ultimoResultado.acceso">
                <div class="bg-rose-950/40 border-2 border-rose-500 rounded-2xl p-8 text-center flex flex-col items-center justify-center h-full min-h-[350px] shadow-2xl shadow-rose-950/50">
                    <div class="w-24 h-24 rounded-full bg-rose-500/20 text-rose-400 border border-rose-500/40 flex items-center justify-center mb-4">
                        <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <span class="px-3 py-1 bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold rounded-full uppercase tracking-widest mb-2">Acceso Denegado</span>
                    <h3 class="text-2xl font-extrabold text-white" x-text="ultimoResultado.mensaje || 'Membresía Vencida o Inactiva'"></h3>
                    
                    <div class="mt-6 pt-4 border-t border-rose-900/60 w-full max-w-xs flex justify-between text-xs text-rose-200">
                        <span>Estatus:</span>
                        <strong class="text-white" x-text="ultimoResultado.estatus"></strong>
                    </div>
                </div>
            </template>

        </div>
    </div>
</div>

<!-- Scripts del Lector HTML5 QR -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function checkinApp() {
        return {
            modoManual: false,
            cedulaManual: '',
            ultimoResultado: null,
            html5QrcodeScanner: null,

            iniciarLector() {
                this.html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
                this.html5QrcodeScanner.render((qrData) => this.procesarQr(qrData));
            },

            async procesarQr(qrData) {
                try {
                    let res = await fetch('/api/checkin/qr', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + '{{ session('sanctum_token') }}'
                        },
                        body: JSON.stringify({
                            qr_data: qrData,
                            sede_id: '{{ auth()->user()->sede_id }}'
                        })
                    });
                    
                    this.ultimoResultado = await res.json();
                    this.reproducirSonido(this.ultimoResultado.acceso);
                } catch(e) {
                    this.ultimoResultado = { acceso: false, mensaje: 'Error de red al procesar validación' };
                }
            },

            async procesarManual() {
                if (!this.cedulaManual) return;

                try {
                    let res = await fetch('/api/checkin/manual', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + '{{ session('sanctum_token') }}'
                        },
                        body: JSON.stringify({
                            cedula: this.cedulaManual,
                            sede_id: '{{ auth()->user()->sede_id }}'
                        })
                    });

                    this.ultimoResultado = await res.json();
                    this.reproducirSonido(this.ultimoResultado.acceso);
                    this.cedulaManual = '';
                } catch(e) {
                    this.ultimoResultado = { acceso: false, mensaje: 'Error al consultar la cédula' };
                }
            },

            reproducirSonido(permitido) {
                // Tono simple vía Web Audio API
                let ctx = new (window.AudioContext || window.webkitAudioContext)();
                let osc = ctx.createOscillator();
                osc.type = 'sine';
                osc.frequency.value = permitido ? 880 : 220; // Agudo para bien, grave para error
                osc.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.3);
            }
        }
    }
</script>
@endsection