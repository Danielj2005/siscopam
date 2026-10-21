@extends('layouts.app')

@section('title', 'Punto de Venta (POS)')

@section('content')
<div x-data="posApp()" class="grid grid-cols-1 lg:grid-cols-12 gap-8 h-[calc(100vh-7rem)]">

    <!-- Catálogo de Productos y Buscador (Izquierda) -->
    <div class="lg:col-span-7 flex flex-col space-y-4 h-full">
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4 flex items-center justify-between gap-4">
            <div class="relative flex-1">
                <svg class="w-5 h-5 absolute left-3 top-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="busqueda" placeholder="Buscar por código de barras o nombre de producto..."
                    class="w-full pl-10 pr-4 py-2 bg-gray-900 border border-gray-800 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="text-right whitespace-nowrap">
                <span class="text-[10px] text-gray-400 block uppercase font-semibold">Tasa Aplicada</span>
                <span class="text-sm font-bold text-emerald-400" x-text="tasaBcv.toFixed(2) + ' VES'"></span>
            </div>
        </div>

        <!-- Grid de Tarjetas de Productos -->
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4 flex-1 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 gap-3 align-content-start">
            <template x-for="prod in productosFiltrados" :key="prod.id">
                <div @click="agregarAlCarrito(prod)" 
                     class="bg-gray-900 border border-gray-800 hover:border-indigo-500/50 rounded-xl p-3 cursor-pointer transition flex flex-col justify-between group">
                    <div>
                        <span class="text-[10px] font-medium text-indigo-400 block" x-text="prod.codigo_barras || 'S/C'"></span>
                        <h4 class="text-xs font-semibold text-white group-hover:text-indigo-300 transition line-clamp-2 mt-1" x-text="prod.nombre"></h4>
                    </div>
                    <div class="mt-3 pt-2 border-t border-gray-800/60 flex items-center justify-between">
                        <div>
                            <span class="text-sm font-bold text-emerald-400" x-text="'$' + parseFloat(prod.precio_usd).toFixed(2)"></span>
                            <span class="text-[10px] text-gray-400 block" x-text="'Bs. ' + (prod.precio_usd * tasaBcv).toFixed(2)"></span>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-800 text-gray-300" x-text="'Stock: ' + prod.stock_actual"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Carrito de Venta y Procesamiento (Derecha) -->
    <div class="lg:col-span-5 bg-gray-950 border border-gray-800 rounded-2xl p-6 flex flex-col justify-between h-full shadow-2xl">
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-gray-800">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Orden de Venta POS
                </h3>
                <button @click="vaciarCarrito()" class="text-xs text-rose-400 hover:text-rose-300 transition">Vaciar</button>
            </div>

            <!-- Lista de Ítems en Carrito -->
            <div class="my-4 max-h-56 overflow-y-auto divide-y divide-gray-900 pr-1">
                <template x-for="(item, index) in carrito" :key="index">
                    <div class="py-2.5 flex items-center justify-between gap-2">
                        <div class="flex-1">
                            <h5 class="text-xs font-semibold text-white" x-text="item.nombre"></h5>
                            <span class="text-[10px] text-gray-400" x-text="'$' + item.precio_usd + ' x ' + item.cantidad"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="disminuirCantidad(index)" class="w-6 h-6 bg-gray-900 border border-gray-800 rounded text-gray-300 flex items-center justify-center font-bold text-xs hover:bg-gray-800">-</button>
                            <span class="text-xs font-bold text-white w-4 text-center" x-text="item.cantidad"></span>
                            <button @click="aumentarCantidad(index)" class="w-6 h-6 bg-gray-900 border border-gray-800 rounded text-gray-300 flex items-center justify-center font-bold text-xs hover:bg-gray-800">+</button>
                        </div>
                        <span class="text-xs font-bold text-emerald-400 w-16 text-right" x-text="'$' + (item.precio_usd * item.cantidad).toFixed(2)"></span>
                    </div>
                </template>
                <template x-if="carrito.length === 0">
                    <p class="py-8 text-center text-xs text-gray-500">Selecciona productos del catálogo para armar la venta.</p>
                </template>
            </div>
        </div>

        <!-- Totales y Opciones de Pago -->
        <div class="pt-4 border-t border-gray-800 space-y-4">
            <div class="bg-gray-900/80 p-4 rounded-xl border border-gray-800 space-y-2">
                <div class="flex justify-between text-xs text-gray-400">
                    <span>Subtotal USD:</span>
                    <span class="font-semibold text-white" x-text="'$' + totalUsd().toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-xs text-gray-400">
                    <span>Equivalente VES (BCV):</span>
                    <span class="font-semibold text-white" x-text="'Bs. ' + totalVes().toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-base font-bold text-white pt-2 border-t border-gray-800">
                    <span>TOTAL A PAGAR:</span>
                    <span class="text-emerald-400" x-text="'$' + totalUsd().toFixed(2)"></span>
                </div>
            </div>

            <!-- Opciones de Cobro -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[10px] text-gray-400 mb-1">Método de Pago</label>
                    <select x-model="metodoPago" class="w-full px-3 py-2 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
                        <option value="EFECTIVO_USD">Efectivo USD</option>
                        <option value="PAGO_MOVIL">Pago Móvil VES</option>
                        <option value="EFECTIVO_VES">Efectivo VES</option>
                        <option value="PUNTO">Punto de Venta</option>
                        <option value="ZELLE">Zelle</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] text-gray-400 mb-1">Referencia</label>
                    <input type="text" x-model="referencia" placeholder="N° Transferencia"
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-800 rounded-xl text-xs text-white outline-none">
                </div>
            </div>

            <button @click="procesarVenta()" :disabled="carrito.length === 0 || procesando"
                class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 disabled:bg-gray-800 text-white font-bold rounded-xl text-sm transition shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2">
                <span x-text="procesando ? 'Procesando Venta...' : 'Completar Venta e Imprimir Ticket'"></span>
            </button>
        </div>
    </div>
</div>

<script>
    function posApp() {
        return {
            busqueda: '',
            tasaBcv: {{ app(\App\Services\BcvService::class)->obtenerTasaUsd() }},
            productos: @json($productos ?? []),
            carrito: [],
            metodoPago: 'EFECTIVO_USD',
            referencia: '',
            procesando: false,

            get productosFiltrados() {
                if (!this.busqueda) return this.productos;
                return this.productos.filter(p => 
                    p.nombre.toLowerCase().includes(this.busqueda.toLowerCase()) || 
                    (p.codigo_barras && p.codigo_barras.includes(this.busqueda))
                );
            },

            agregarAlCarrito(producto) {
                let item = this.carrito.find(i => i.id === producto.id);
                if (item) {
                    if (item.cantidad < producto.stock_actual) item.cantidad++;
                } else {
                    this.carrito.push({
                        id: producto.id,
                        nombre: producto.nombre,
                        precio_usd: parseFloat(producto.precio_usd),
                        cantidad: 1,
                        stock_max: producto.stock_actual
                    });
                }
            },

            aumentarCantidad(index) {
                if (this.carrito[index].cantidad < this.carrito[index].stock_max) {
                    this.carrito[index].cantidad++;
                }
            },

            disminuirCantidad(index) {
                if (this.carrito[index].cantidad > 1) {
                    this.carrito[index].cantidad--;
                } else {
                    this.carrito.splice(index, 1);
                }
            },

            vaciarCarrito() {
                this.carrito = [];
            },

            totalUsd() {
                return this.carrito.reduce((sum, i) => sum + (i.precio_usd * i.cantidad), 0);
            },

            totalVes() {
                return this.totalUsd() * this.tasaBcv;
            },

            async procesarVenta() {
                this.procesando = true;

                try {
                    let response = await fetch('/api/pos/ventas', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + '{{ session('sanctum_token') }}'
                        },
                        body: JSON.stringify({
                            sede_id: '{{ auth()->user()->sede_id }}',
                            moneda_pago: this.metodoPago.includes('USD') ? 'USD' : 'VES',
                            metodo_pago: this.metodoPago,
                            referencia: this.referencia,
                            productos: this.carrito.map(i => ({ id: i.id, cantidad: i.cantidad }))
                        })
                    });

                    let res = await response.json();

                    if (response.ok) {
                        // Abrir ventana emergente para imprimir el ticket térmico
                        window.open('/tickets/venta/' + res.venta.id + '/imprimir', '_blank', 'width=400,height=600');
                        this.vaciarCarrito();
                        this.referencia = '';
                        alert('Venta procesada con éxito');
                    } else {
                        alert(res.message || 'Error al procesar la venta');
                    }
                } catch (e) {
                    alert('Error de conexión al servidor');
                } finally {
                    this.procesando = false;
                }
            }
        }
    }
</script>
@endsection