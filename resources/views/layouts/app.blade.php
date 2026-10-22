<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-900 text-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SisCoPaM' }} - Gestión de Gimnasio</title>

    <!-- PWA Manifest & Theme Color -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0f172a">

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets (Tailwind CSS + Alpine.js / Scripts) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="h-full font-sans antialiased bg-gray-900 text-gray-100" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- Sidebar Móvil (Drawer) -->
        <div x-show="sidebarOpen" 
             class="fixed inset-0 z-50 flex md:hidden" 
             x-ref="dialog" 
             aria-modal="true" 
             style="display: none;">
            
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm" 
                 @click="sidebarOpen = false"></div>

            <div x-show="sidebarOpen" 
                x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-900 border-r border-gray-800 pt-5 pb-4">
                
                <div class="px-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <img src="./img/siscopam.png" class="block h-12 w-12 fill-current text-gray-800 dark:text-gray-200" alt="Logo de siscopam">
                        <span class="font-bold text-lg text-white tracking-wide">SisCoPaM</span>
                    </div>
                    <button @click="sidebarOpen = false" class="text-gray-400 hover:text-white p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <nav class="mt-6 flex-1 px-3 space-y-1">
                    @include('layouts.partials.navigation-links')
                </nav>
            </div>
        </div>

        <!-- Sidebar Desktop -->
        <aside class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 bg-gray-950 border-r border-gray-800">
            <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center gap-3 px-6 pb-5 border-b border-gray-800/60">
                    <img src="./img/siscopam.png" class="block h-12 w-12 fill-current text-gray-800 dark:text-gray-200" alt="Logo de siscopam">
                    <div>
                        <span class="font-bold text-lg text-white tracking-wide block leading-none">SisCoPaM</span>
                        <span class="text-xs text-indigo-400 font-medium">Create Tech Solutions</span>
                    </div>
                </div>

                <nav class="mt-6 flex-1 px-3 space-y-1">
                    @include('layouts.partials.navigation-links')
                </nav>

                <div class="px-3 mt-auto">
                    <div class="bg-gray-900/80 border border-gray-800 rounded-xl p-3 flex items-center justify-between">
                        <a href="{{ route('logout') }}" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition shadow-lg shadow-indigo-600/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Cerrar Sesión
                        </a>
                    </div>
                </div>

                <!-- Panel Tasa BCV Flotante -->
                <div class="px-3 mt-auto">
                    <div class="bg-gray-900/80 border border-gray-800 rounded-xl p-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-semibold tracking-wider text-gray-400 uppercase block">Tasa Oficial BCV</span>
                            <span class="text-sm font-bold text-emerald-400">
                                {{ number_format(app(\App\Services\BcvService::class)->obtenerTasaUsd(), 2, ',', '.') }} VES
                            </span>
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            USD
                        </span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Área Principal de Contenido -->
        <div class="md:pl-64 flex flex-col flex-1 w-full">
            
            <!-- Navbar Superior -->
            <header class="sticky top-0 z-40 bg-gray-900/80 backdrop-blur-md border-b border-gray-800 h-16 flex items-center justify-between px-4 md:px-8">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="md:hidden text-gray-400 hover:text-white p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-lg font-semibold text-white">{{ $header ?? 'Panel de Control' }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Notificaciones In-App -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-white rounded-lg hover:bg-gray-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-indigo-500 ring-2 ring-gray-900"></span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" style="display: none;" 
                            class="absolute right-0 mt-2 w-80 bg-gray-950 border border-gray-800 rounded-xl shadow-2xl py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-800 flex justify-between items-center">
                                <span class="text-xs font-semibold text-gray-300">Notificaciones</span>
                                <span class="text-[10px] text-indigo-400 font-medium">{{ auth()->user()->unreadNotifications->count() }} nuevas</span>
                            </div>
                            <div class="max-h-64 overflow-y-auto divide-y divide-gray-900">
                                @forelse(auth()->user()->notifications->take(5) as $notificacion)
                                    <div class="p-3 hover:bg-gray-900 transition">
                                        <p class="text-xs font-medium text-gray-200">{{ $notificacion->data['titulo'] ?? 'Alerta' }}</p>
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $notificacion->data['mensaje'] ?? '' }}</p>
                                    </div>
                                @empty
                                    <p class="p-4 text-xs text-gray-500 text-center">Sin notificaciones</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Perfil del Usuario -->
                    <div class="flex items-center gap-3 pl-3 border-l border-gray-800">
                        <div class="w-8 h-8 rounded-full bg-indigo-600/30 text-indigo-400 border border-indigo-500/30 flex items-center justify-center font-bold text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-xs font-medium text-gray-200 block leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-gray-400 block">{{ auth()->user()->role->nombre ?? 'Usuario' }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Contenido Dinámico de la Vista -->
            <main class="flex-1 p-4 md:p-8">
                <!-- Alertas de Sesión -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm flex items-center justify-between">
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>