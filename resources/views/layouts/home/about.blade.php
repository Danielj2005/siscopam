
<section id="about" class="w-full dark:bg-gray-900 bg-gray-400/20 min-h-screen ">

    <!-- Section Title -->
    <div class=" dark:text-white flex flex-wrap items-center justify-center mb-4 text-center" style="text-wrap: balance !important;">
        <h2 class="border-b-2 border-b-slate-800 dark:border-b-slate-200 dark:text-white h-16 mb-8 p-4 w-[20rem]"
            style="font-size: 2rem; line-height: 1rem; margin: 5rem 0;" data-aos="fade-up" data-aos-delay="200">About Us</h2>

        <p class="text-slate-800 dark:text-slate-400" data-aos="fade-up" data-aos-delay="300">SISCOPAM nació de la necesidad de simplificar la gestión de pagos y mensualidades en gimnasios. Nuestra misión es ofrecer una solución eficiente y fácil de usar para que los administradores puedan concentrarse en hacer crecer su negocio.</p>

    </div><!-- End Section Title -->

    <div class="dark:text-white" data-aos="fade-up" data-aos-delay="200">

        <div class="gap-4 grid sm:grid-cols-1 md:grid-cols-2 items-center justify-center">
            <div class="">
                <img src="./img/siscopam.png" class="img-fluid" alt="Logo de siscopam">
            </div>
            <div class="">
                <h2 class=" text-slate-800 dark:text-slate-300 text-2xl">Sistema de Control de Pagos y Mensualidades.</h2>
                <p class="fst-italic py-3 text-slate-800 dark:text-slate-400" style="text-wrap: balance !important;">
                    Nuestro sistema te ayuda a gestionar de manera eficiente los pagos y mensualidades de tu gimnasio, facilitando el control y seguimiento de tus clientes. Te ofrecemos:
                </p>
                <div class="grid grid-cols-1 text-md">
                    <ul class="">
                        @php
                            $features = [
                                'Seguimiento y Control Centralizado de Pagos.',
                                'Notificaciones Automáticas de Pagos (Email).',
                                'Gestión Detallada de Planes, Paquetes y Suscripciones.',
                                'Base de Datos y Gestión Integral de Clientes.',
                                'Emisión de Facturas de Pago en formato PDF.',
                                'Generación de Reportes Financieros y de Ganancias.',
                                'Identificación y Control de Cuentas por Cobrar (Deudores).',
                                'Respaldo Seguro y Garantizado de Toda la Información de Pagos.',
                                'Acceso Rápido y Fácil a la Información de Clientes y Transacciones.'
                                ];
                        @endphp

                        @foreach ($features as $feature)
                            <li class="flex gap-4 items-center justify-start text-slate-800 dark:text-slate-400">
                                <x-icon-chevron-right class="h-9 fill-current text-gray-800 dark:text-gray-200" />
                                <strong>{{ $feature }}</strong>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>

</section>
