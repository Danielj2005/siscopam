@extends('layouts.app')

@section('title', 'Mi Portal Atleta')

@section('content')
<div x-data="portalAtletaApp()" class="max-w-md mx-auto space-y-6 pb-20">

    <!-- Tarjeta de Perfil y Estatus de Membresía -->
    <div class="bg-gradient-to-br from-indigo-900/60 to-gray-950 border border-indigo-500/30 rounded-3xl p-6 shadow-2xl relative overflow-hidden">
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl"></div>

        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 font-bold flex items-center justify-center text-base">
                    {{ strtoupper(substr($cliente->nombre ?? 'A', 0, 1)) }}{{ strtoupper(substr($cliente->apellido ?? 'T', 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-base font-bold text-white leading-tight">{{ $cliente->nombre }} {{ $cliente->apellido }}</h2>
                    <span class="text-xs text-gray-400 font-mono">C.I.: {{ $cliente->cedula }}</span>
                </div>
            </div>

            <!-- Badge Estado -->
            @php
                $esActivo = $cliente->fecha_vencimiento && now()->lte($cliente->fecha_vencimiento);
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $esActivo ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' }}">
                <span class="w-2 h-2 rounded-full {{ $esActivo ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }} mr-1.5"></span>
                {{ $esActivo ? 'ACTIVA' : 'EXPIRADA' }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-800/80">
            <div>
                <span class="text-[10px] uppercase font-semibold text-gray-400 block">Vencimiento</span>
                <span class="text-sm font-bold text-white">
                    {{ $cliente->fecha_vencimiento ? $cliente->fecha_vencimiento->format('d/m/Y') : 'Sin Plan' }}
                </span>
            </div>
            <div class="text-right">
                <span class="text-[10px] uppercase font-semibold text-gray-400 block">Días Restantes</span>
                <span class="text-sm font-bold {{ $esActivo ? 'text-emerald-400' : 'text-rose-400' }}">
                    {{ $cliente->fecha_vencimiento ? max(0, now()->diffInDays($cliente->fecha_vencimiento, false)) . ' días' : '0 días' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Pestañas de Navegación del Portal -->
    <div class="grid grid-cols-2 p-1 bg-gray-950 border border-gray-800 rounded-2xl">
        <button @click="pestana = 'qr'" 
            :class="pestana === 'qr' ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:text-white'"
            class="py-2.5 rounded-xl text-xs font-bold transition">
            Código QR Acceso
        </button>
        <button @click="pestana = 'reportar'" 
            :class="pestana === 'reportar' ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:text-white'"
            class="py-2.5 rounded-xl text-xs font-bold transition">
            Reportar Pago Móvil
        </button>
    </div>

    <!-- VISTA 1: CÓDIGO QR PARA TORNIQUETE -->
    <div x-show="pestana === 'qr'" class="bg-gray-950 border border-gray-800 rounded-3xl p-6 text-center shadow-xl space-y-4">
        <h3 class="text-sm font-semibold text-gray-300">Tu Pase de Entrada Digital</h3>
        <p class="text-xs text-gray-500">Muestra este código en el escáner de la recepción para ingresar.</p>

        <!-- Contenedor del QR dinámico -->
        <div class="bg-white p-4 rounded-2xl inline-block border-4 border-indigo-600 shadow-2xl my-2">
            <div id="qrcode-container" class="w-48 h-48 mx-auto flex items-center justify-center">
                <div x-show="cargandoQr" class="text-gray-400 text-xs animate-pulse">Generando Pase QR...</div>
            </div>
        </div>

        <div class="flex items-center justify-center gap-2 text-xs text-gray-400">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Seguro y Cifrado</span>
        </div>

        <button @click="obtenerQr()" class="w-full py-2.5 bg-gray-900 hover:bg-gray-800 text-gray-300 text-xs font-semibold rounded-xl transition border border-gray-800">
            Actualizar Código QR
        </button>
    </div>

    <!-- VISTA 2: FORMULARIO DE REPORTE DE PAGO MÓVIL / ZELLE -->
    <div x-show="pestana === 'reportar'" class="bg-gray-950 border border-gray-800 rounded-3xl p-6 shadow-xl space-y-5" style="display: none;">
        <div>
            <h3 class="text-sm font-bold text-white">Reportar Transferencia / Pago Móvil</h3>
            <p class="text-xs text-gray-400 mt-0.5">Sube los datos de tu pago para renovación rápida de membresía.</p>
        </div>

        <form action="/api/cliente/reportar-pago" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="sede_id" value="{{ $cliente->sede_id }}">

            <div>
                <label class="block text-xs font-medium text-gray-300 mb-1">Método de Pago</label>
                <select name="metodo_pago" required class="w-full px-3 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
                    <option value="PAGO_MOVIL">Pago Móvil VES</option>
                    <option value="TRANSFERENCIA">Transferencia Bancaria</option>
                    <option value="ZELLE">Zelle USD</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Monto (USD $)</label>
                    <input type="number" step="0.01" name="monto_usd" value="25.00" required
                        class="w-full px-3 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white font-bold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">N° Referencia</label>
                    <input type="text" name="numero_referencia" required placeholder="Últimos 6 dígitos"
                        class="w-full px-3 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white font-mono outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-300 mb-1">Fecha del Pago</label>
                <input type="date" name="fecha_pago" value="{{ date('Y-m-d') }}" required
                    class="w-full px-3 py-2.5 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-300 mb-1">Comprobante / Captura (Opcional)</label>
                <input type="file" name="comprobante" accept="image/*"
                    class="w-full text-xs text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-900 file:text-indigo-400 hover:file:bg-gray-800">
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition shadow-lg shadow-emerald-600/30">
                Enviar Reporte de Pago
            </button>
        </form>
    </div>

</div>

<!-- Librería para Renderizado QR -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    function portalAtletaApp() {
        return {
            pestana: 'qr',
            cargandoQr: true,

            init() {
                this.obtenerQr();
            },

            async obtenerQr() {
                this.cargandoQr = true;
                let container = document.getElementById('qrcode-container');
                container.innerHTML = '';

                try {
                    let res = await fetch('/api/cliente/mi-qr', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + '{{ session('sanctum_token') }}'
                        }
                    });

                    let data = await res.json();

                    if (data.qr_token) {
                        new QRCode(container, {
                            text: data.qr_token,
                            width: 180,
                            height: 180,
                            colorDark: "#0f172a",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.H
                        });
                    }
                } catch(e) {
                    container.innerHTML = '<span class="text-xs text-rose-500">Error al cargar el pase QR.</span>';
                } finally {
                    this.cargandoQr = false;
                }
            }
        }
    }
</script>
@endsection