<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>siscopam</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 ">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ auth()->user()->role->nombre === 'SUPER_ADMIN' ? url('/superadmin/dashboard') : url('/admin/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Registrar</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="lg:p-8 max-w-7xl mx-auto overflow-hidden p-6">
                <div class="flex flex-wrap items-center justify-center mb-6 min-h-screen w-full" data-aos="fade-up" data-aos-delay="200">
                    <img src="./img/siscopam.png" class="img-fluid w-[10rem] h-[10rem] object-fill" alt="image of SISCOPAM">
                    <h3 class="dark:text-[#fff] text-slate-600 font-bold text-uppercase text-[15rem]" style="font-size:9rem;">SISCOPAM</h3>
                </div>

                @include('layouts.home.about')

                @include('layouts.home.service')

                @include('layouts.home.contact')
                @include('layouts.home.footer')
            </div>
        </div>
    </body>
</html>
