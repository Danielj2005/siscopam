<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Venta / Caja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                                
                    <nav class="d-none text-white px-6 py-2 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <h1 class="font-black text-xl tracking-tight">VENE-POS</h1>
                            <span class="bg-blue-700 px-3 py-1 rounded-full text-xs font-mono border border-slate-500">Terminal #01</span>
                        </div>
                        
                        <div class="flex items-center gap-3 px-4 py-1 rounded-lg border border-blue-400">
                            <span class="text-xs font-bold text-blue-200">TASA BCV:</span>
                            <div class="flex items-center">
                                <span class="text-sm font-bold mr-1">Bs.</span>
                                <input type="number" value="36.50" step="0.01" class="bg-transparent border-none w-full text-sm font-black focus:ring-0 outline-none text-yellow-400" title="Click para editar tasa">
                            </div>
                            <span class="text-[10px] text-blue-300 italic">Hoy 19:18</span>
                        </div>
                    </nav>

                    <div class="flex flex-col md:flex-row h-ful overflow-hidden">
                        
                        <div class="flex-1 flex flex-col  overflow-hidden">
                            
                            <div class="p-2 border-b">
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 group-focus-within:text-slate-200 transition-colors">
                                        🔍
                                    </span>
                                    <input 
                                        type="text" placeholder="Escribe Nombre, Código de Barras o Presentación..." 
                                        class="w-full pl-12 pr-4 py-2 bg-transparent border-2 border-slate-200 rounded-2xl focus:border-blue-500 focus:bg-slate-300 transition-all outline-none text-lg shadow-sm"
                                        autofocus
                                    >
                                </div>
                            </div>

                            <div class="flex-1 overflow-y-auto px-4">
                                <table class="w-full text-left">
                                    <thead class="sticky top-0 bg-slate-800 z-10 border-b">
                                        <tr class="text-slate-200 uppercase text-[10px] font-bold tracking-widest">
                                            <th class="py-2">Descripción</th>
                                            <th class="py-2 w-12 text-center">Cant.</th>
                                            <th class="py-2 text-right">Precio ($ / Bs.)</th>
                                            <th class="py-2 text-right">Total Bs.</th>
                                            <th class="py-2 text-center">Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <tr class="hover:bg-blue-50/20 transition-colors">
                                            <td class="py-4">
                                                <div class="font-bold text-gray-800 dark:text-slate-300 leading-tight">Harina Pan 1kg</div>
                                                <div class="text-[11px] text-blue-400 font-mono">759100100012 • Maíz Precocida</div>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center justify-center bg-slate-400 rounded-lg w-20 mx-auto">
                                                    <button class="px-2 font-bold hover:text-red-600">-</button>
                                                    <span class="px-2 text-sm font-black dark:text-slate-200 ">2</span>
                                                    <button class="px-2 font-bold hover:text-green-600">+</button>
                                                </div>
                                            </td>
                                            <td class="py-4 text-right">
                                                <div class="text-md font-bold text-green-500 ">$ 1.20</div>
                                                <div class="text-md text-slate-200">Bs. 55.4325.80</div>
                                            </td>
                                            <td class="py-4 text-right">
                                                <div class="text-base font-black text-blue-500 italic">Bs. 38.8651.60</div>
                                                <div class="text-md text-green-500">Ref. $ 2.40</div>
                                            </td>
                                            <td class="py-4 text-center ">
                                                <div class="flex items-center justify-center mx-auto">
                                                    <button class="text-gray-300 hover:text-red-900 transition-colors rounded-full w-12 h-12  bg-red-500/60">✕</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="w-full md:w-96 border-l border-slate-200 flex flex-col shadow-2xl">
                            <div class="p-4 flex-1 space-y-6">
                                <div>
                                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-4">Resumen de Cuenta</h3>
                                    
                                    <div class="space-y-3 bg-slate-500 p-2 rounded-xl border border-gray-200 shadow-sm">
                                        <div class="flex justify-between items-baseline">
                                            <span class="text-slate-800 text-md">TOTAL REF.</span>
                                            <span class="text-xl font-black text-slate-200">$ 2.47</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-slate-600 p-2 rounded-2xl text-white relative overflow-hidden">
                                    <div class="relative z-10 p-2">
                                        <span class="text-md text-slate-300 font-bold  uppercase">Total a Pagar (Bs.)</span>
                                        <div class="text-xl text-slate-200 font-black mt-1 leading-none">
                                            Bs. 90.15
                                        </div>
                                    </div>
                                    <p class="absolute right-2 -bottom-12 text-[5rem]  rotate-12 ">🇻🇪</p>
                                </div>

                                <div class="grid grid-cols-2 gap-1">
                                    <button class="flex flex-col items-center justify-center p-2 bg-slate-600 border-2 border-gray-100 rounded-xl hover:border-blue-500 transition-all group">
                                        <span class="text-xl">📱</span>
                                        <span class="text-[10px] font-black text-gray-50:">PAGO MÓVIL</span>
                                    </button>
                                    <button class="flex flex-col items-center justify-center p-2 bg-slate-600 border-2 border-gray-100 rounded-xl hover:border-blue-500 transition-all group">
                                        <span class="text-xl">💵</span>
                                        <span class="text-[10px] font-black text-gray-50:">DIVISAS</span>
                                    </button>
                                    <button class="flex flex-col items-center justify-center p-2 bg-slate-600 border-2 border-gray-100 rounded-xl hover:border-blue-500 transition-all group">
                                        <span class="text-xl">💳</span>
                                        <span class="text-[10px] font-black text-gray-50:">DÉBITO</span>
                                    </button>
                                    <button class="flex flex-col items-center justify-center p-2 bg-slate-600 border-2 border-gray-100 rounded-xl hover:border-blue-500 transition-all group">
                                        <span class="text-xl">🧾</span>
                                        <span class="text-[10px] font-black text-gray-50:">BIOPAGO</span>
                                    </button>
                                </div>
                            </div>

                            <div class="p-6  border-t">
                                <button class="w-full bg-green-500 hover:bg-green-600 text-white font-black py-2 rounded-xl text-lg shadow-lg flex flex-col items-center justify-center transition-transform active:scale-95 leading-tight">
                                    <span>FINALIZAR VENTA</span>
                                    <span class="text-[10px] font-normal opacity-90 uppercase tracking-tighter">Generar Ticket</span>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
