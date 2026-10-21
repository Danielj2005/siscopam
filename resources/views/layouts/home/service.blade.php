
<section id="services" class="w-full dark:bg-gray-900 bg-gray-400/20 min-h-screen ">

    <!-- Section Title -->
    <div class="dark:text-white flex flex-wrap items-center justify-center mb-[5rem] text-center">
        <h2 class="border-b-2 border-b-slate-800 dark:border-b-slate-200 dark:text-white h-16 mb-8 p-4 w-[40rem]"
            style="font-size: 2rem; line-height: 1rem; margin: 5rem 0;" data-aos="fade-up" data-aos-delay="200">Planes Flexibles y Precios Transparentes</h2>

        <p class="mb-8 text-xl text-slate-800 dark:text-slate-400" style="text-wrap: balance;" data-aos="fade-up" data-aos-delay="300">Elige la solución que impulsa tu negocio sin compromisos. ¡Paga solo por lo que usas!</p>

    </div>

    <div class="">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            @php
                $totalManagement = "Todas las funciones del Plan Control Total,
                    Base de Datos y Gestión Integral de Clientes (hasta 250 Clientes).,
                    Notificaciones Automáticas de Pagos (vía email).,
                    Gestión Detallada de Planes y Suscripciones.,
                    Reportes Financieros Avanzados y Personalizados.,
                    Respaldo Seguro y Garantizado de Toda la Información.,
                    Soporte Prioritario 24/7.";

                $totalControl = "Seguimiento y Control de Pagos.,Gestión de Clientes Ilimitada.,Emisión de Tiket de Pago en formato PDF.,Identificación y Control de Cuentas por Cobrar (Deudores).,Generación de Reportes Financieros básicos.";
            @endphp

            <x-card-home title="Gestion Pro" iconClass="bg-indigo-800/20" currentCoin="$" price="30" time="/ mes" 
                popular="1" list="{{$totalControl}}"  
                class="scale-100 p-6 bg-slate-400 dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500" 
                data-aos="fade-up" data-aos-delay="200">
                    <x-slot name="icon"> <x-icon-star class="text-indigo-400  w-6 h-6" /> </x-slot>
                    <x-slot name="title"> Control Total</x-slot>
                    <x-slot name="subtitle">La solución esencial para organizar tus cobros y clientes, con un pago único.</x-slot>
            </x-card-home >

            <x-card-home title="Gestion Pro" iconClass="bg-indigo-800/20" currentCoin="$" price="30" time="/ mes" 
                list="{{$totalManagement}}"  
                class="scale-100 p-6 bg-slate-400 dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500" 
                data-aos="fade-up" data-aos-delay="200">
                    <x-slot name="icon"> <x-icon-star class="text-indigo-400  w-6 h-6" /> </x-slot>
                    <x-slot name="title"> Gestión Pro</x-slot>
                    <x-slot name="subtitle"> Control total y automatización para el crecimiento constante.</x-slot>
            </x-card-home >

        </div>
    </div>

</section>