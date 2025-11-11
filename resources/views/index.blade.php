<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>SISCOPAM</title>
        <meta content="sistema de control de pagos y mensualidades de gimnasios" name="description">
        <meta content="Gimnasios, Gestión de Pagos, Mensualidades, Administración de Gimnasios" name="keywords">
        <meta content="Daniel Barrueta" name="author">

        <!-- Favicons -->
        <link href="./siscopam.png" rel="icon">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com" rel="preconnect">
        <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <!-- Vendor CSS Files -->
        {{-- <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> --}}
        {{-- <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet"> --}}
        <link href="../vendor/aos/aos.css" rel="stylesheet">
        <link href="../vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
        <link href="../vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

        <!-- Main CSS File -->
        <link href="../resources/css/main.css" rel="stylesheet">
        <style>
        .logo{
            width : 70px !important;
            height : 70px !important;
        }
        </style>
    </head>

    <body class="index-page bg-dark-subtle" data-bs-theme="dark">

        <header id="header" class="header d-flex flex-column justify-content-center">
            <i class="header-toggle d-xl-none bi bi-list"></i>
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active"><i class="bi bi-house navicon"></i><span>Página Principal</span></a></li>
                    <li><a href="#about"><i class="bi bi-person navicon"></i><span>Quiénes Somos</span></a></li>
                    <li><a href="#services"><i class="bi bi-file-earmark-text navicon"></i><span>Planes y Funciones</span></a></li>
                    <li><a href="#contact"><i class="bi bi-envelope navicon"></i><span>Contacto</span></a></li>
                </ul>
            </nav>
        </header>

        <main class="main">

            <!-- Hero Section -->
            <section id="hero" class="hero section dark-background">

                <img src="wallpaper2.jpg" alt="">

                <div class="container" data-aos="zoom-out">
                    <div class="row justify-content-center">
                        <div class="col-lg-9">

                            <h2 class="d-flex align-items-center">
                                <img class="logo position-relative" src="siscopam.png" alt="Logo de Siscopam">
                                SISCOPAM
                            </h2>
                            <p>Simplifica la Gestión de Pagos y Mensualidades de tu Gimansio.</p>

                            <div class="social-links">
                                <a target="_blank" href="#"><i class="bi bi-facebook text-primary"></i></a>
                                <a target="_blank" href="https://api.whatsapp.com/send?phone=584125238909"><i class="text-success bi bi-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </section><!-- /Hero Section -->

            <!-- About Section -->
            <section id="about" class="about section bg-dark-subtle">

                <!-- Section Title -->
                <div class="container section-title text-white" style="text-wrap: balance !important;" data-aos="fade-up">
                    <h2 class="text-white">Sobre Nosotros</h2>
                    <p>"SISCOPAM" nació de la necesidad de simplificar la gestión de pagos y mensualidades en gimnasios. Nuestra misión es ofrecer una solución eficiente y fácil de usar para que los administradores puedan concentrarse en hacer crecer su negocio.</p>
                </div><!-- End Section Title -->

                <div class="container  text-white" data-aos="fade-up" data-aos-delay="200">

                    <div class="row gy-4 justify-content-center">
                        <div class="col-lg-4 ">
                            <img src="siscopam.png" class="img-fluid" alt="Logo de siscopam">
                        </div>
                        <div class="col-lg-8 content">
                            <h2 class="text-white">Sistema de Control de Pagos y Mensualidades.</h2>
                            <p class="fst-italic py-3" style="text-wrap: balance !important;">
                                Nuestro sistema te ayuda a gestionar de manera eficiente los pagos y mensualidades de tu gimnasio, facilitando el control y seguimiento de tus clientes. Te ofrecemos:
                            </p>
                            <div class="row">
                                <div class="col-lg-6">
                                    <ul>
                                        <li><i class="bi bi-chevron-right"></i> <strong>Seguimiento y Control Centralizado de Pagos.</strong> </li>
                                        <li><i class="bi bi-chevron-right"></i> <strong>Notificaciones Automáticas de Pagos (Email).</strong> </li>
                                        <li><i class="bi bi-chevron-right"></i> <strong>Gestión Detallada de Planes, Paquetes y Suscripciones.</strong> </li>
                                        <li><i class="bi bi-chevron-right"></i> <strong>Base de Datos y Gestión Integral de Clientes.</strong> </li>
                                    </ul>
                                </div>
                                <div class="col-lg-6">
                                    <ul>
                                        <li><i class="bi bi-chevron-right"></i> <strong>Emisión de Facturas de Pago en formato PDF.</strong> </li>
                                        <li><i class="bi bi-chevron-right"></i> <strong>Generación de Reportes Financieros y de Ganancias..</strong> </li>
                                        <li><i class="bi bi-chevron-right"></i> <strong>Identificación y Control de Cuentas por Cobrar (Deudores).</strong> </li>
                                        <li><i class="bi bi-chevron-right"></i> <strong>Respaldo Seguro y Garantizado de Toda la Información de Pagos.</strong> </li>
                                        <li><i class="bi bi-chevron-right"></i> <strong>Acceso Rápido y Fácil a la Información de Clientes y Transacciones.</strong> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </section>


            <section id="services" class="services section bg-dark-subtle">

                <div class="container section-title" data-aos="fade-up">
                <h2 class="text-white">Planes Flexibles y Precios Transparentes</h2>
                <p class="text-white">Elige la solución que impulsa tu negocio sin compromisos. ¡Paga solo por lo que usas!</p>
                </div>

                <div class="container">

                <div class="row gy-1 justify-content-center">

                    <div class="col-10 col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-4 service-item item-cyan position-relative pricing-card bg-dark text-white-50 border border-2">
                        <div class="icon h-auto"> <i class="bi bi-gem"></i> </div>

                        <div class="pricing-header text-white-50">
                        <h3 class="plan-name text-white">Control Inicial</h3>
                        <span class="price-tag fw-semibold text-white"> <sup>$</sup> <b> 20 </b> <span> / mes</span> </span>
                        <p class="description my-3">La solución esencial para organizar tus cobros y clientes.</p>
                        </div>

                        <ul class="features-list list-unstyled text-start">
                        <li class="mb-2 "><i class="bi bi-check-circle text-success"></i> Seguimiento y Control Centralizado de Pagos.</li>
                        <li class="mb-2 "><i class="bi bi-check-circle text-success"></i> Base de Datos y Gestión Integral de Clientes (hasta 100).</li>
                        <li class="mb-2 "><i class="bi bi-check-circle text-success"></i> Emisión de Facturas de Pago en formato PDF.</li>
                        <li class="mb-2 "><i class="bi bi-x-circle text-danger"></i> Notificaciones Automáticas de Pagos.</li>
                        <li class="mb-2 "><i class="bi bi-x-circle text-danger"></i> Generación de Reportes Financieros.</li>
                        </ul>

                        <a href="#" class="btn btn-buy stretched-link mt-4 btn-outline-light">Comenzar ahora</a>
                    </div>
                    </div>

                    <div class="col-10 col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-4 service-item item-cyan position-relative pricing-card bg-dark text-white-50 border border-2">
                        <div class="icon h-auto"> <i class="bi bi-star"></i> </div>

                        <div class="pricing-header text-white-50">
                        <h3 class="plan-name text-white">Gestión Pro <span class="badge bg-warning text-dark">Popular</span></h3>
                        <span class="price-tag fw-semibold text-white"> <sup>$</sup> <b> 30 </b> <span> / mes</span> </span>
                        <p class="description my-3">Control total y automatización para el crecimiento constante.</p>
                        </div>

                        <ul class="features-list list-unstyled text-start">
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Todas las funciones del Plan <b>Control Inicial</b>.</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Gestión de Clientes Ilimitada.</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> <b>Notificaciones Automáticas de Pagos.</b></li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> <b>Identificación y Control de Cuentas por Cobrar (Deudores).</b> </li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Generación de Reportes Financieros básicos.</li>
                        </ul>

                        <button href="#" class="btn btn-buy stretched-link mt-4 btn-outline-light">¡Empezar a Crecer!</button>
                    </div>
                    </div>

                    <div class="col-10 col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="p-4 service-item item-cyan position-relative pricing-card bg-dark text-white-50 border border-2">
                        <div class="icon h-auto"> <i class="bi bi-shield-check"></i> </div>

                        <div class="pricing-header text-white-50">
                        <h3 class="plan-name text-white">Solución Integral</h3>
                        <span class="price-tag fw-semibold text-white"> <sup>$</sup> <b> 45 </b> <span> / mes</span> </span>
                        <p class="description my-3">Máximo rendimiento, seguridad y soporte para grandes volúmenes.</p>
                        </div>

                        <ul class="features-list list-unstyled text-start">
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Todas las funciones del Plan <b>Gestión Pro</b>.</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> <b>Gestión Detallada de Planes y Suscripciones.</b></li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Reportes Financieros <b>Avanzados y Personalizados.</b></li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> <b>Respaldo Seguro y Garantizado</b> de Toda la Información.</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Soporte Prioritario 24/7.</li>
                        </ul>

                        <a href="#" class="btn btn-buy stretched-link mt-4 btn-outline-light">Solicitar Demo</a>
                    </div>
                    </div>

                </div>

                </div>

            </section>


            <!-- Contact Section -->
            <section id="contact" class="contact section bg-dark-subtle">

                <div class="container section-title" data-aos="fade-up">
                <h2 class="text-white">¿Tienes Preguntas? Contáctanos</h2>
                <p class="text-white">Estamos listos para ayudarte a optimizar tu gestión de pagos y clientes. ¡Hablemos de tu negocio!</p>
                </div>

                <div class="container" data-aos="fade" data-aos-delay="100">

                <div class="row gy-4">
                    <div class="col-lg-4">
                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                        <i class="bi bi-geo-alt flex-shrink-0"></i>
                        <div>
                        <h3 class="text-white">Dirección</h3>
                        <p class="text-white">Turen, Estado Portuguesa.</p>
                        </div>
                    </div>

                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                        <i class="bi bi-envelope flex-shrink-0"></i>
                        <div>
                        <h3 class="text-white">Correo Electrónico</h3>
                        <p class="text-white">dbarrueta42@gmail.com</p>
                        </div>
                    </div>


                    </div>

                    <div class="col-lg-8">
                    <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                        <div class="row gy-4 text-white">

                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control bg-light" placeholder="Your Name" required="">
                        </div>

                        <div class="col-md-6 ">
                            <input type="email" class="form-control bg-light" name="email" placeholder="Your Email" required="">
                        </div>

                        <div class="col-md-12">
                            <input type="text" class="form-control bg-light" name="subject" placeholder="Subject" required="">
                        </div>

                        <div class="col-md-12">
                            <textarea class="form-control bg-light" name="message" rows="6" placeholder="Message" required=""></textarea>
                        </div>

                        <div class="col-md-12 text-center">
                            <div class="loading">cargando</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Tu mensaje fue enviado!</div>

                            <button type="submit">Enviar Mensaje</button>
                        </div>

                        </div>
                    </form>
                    </div>
                </div>
                </div>
            </section>

        </main>

        <footer id="footer" class="footer position-relative bg-dark-subtle">
            <hr class="text-white">
            <div class="container">
                <h3 class="text-white sitename">SISCOPAM</h3>
                <p class="text-white ">El control de tus finanzas es nuestro trabajo. Tu crecimiento, tu prioridad. SISCOPAM un Sistema seguro y confiable para que cada pago cuente, sin perder un solo dato..</p>
                <div class="social-links d-flex justify-content-center">
                    <a class="bg-light" target="_blank" href="#"><i class="bi bi-facebook text-primary"></i></a>
                    <a class="bg-light" target="_blank" href="https://api.whatsapp.com/send?phone=584125238909"><i class="text-success bi bi-whatsapp"></i></a>
                </div>
                <hr class="text-white">

                <div class="container ">
                    <div class="copyright text-white">
                        <span>Copyright</span> <strong class="px-1 sitename">SISCOPAM</strong> <span>All Rights Reserved</span>
                    </div>
                    <div class="credits text-white"> Diseñado por Daniel Barruetas (Create Tech Solution's) </div>
                </div>
            </div>
        </footer>

        <!-- Scroll Top -->
        <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <!-- Preloader -->
        <div id="preloader"></div>

        <!-- Vendor JS Files -->
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../vendor/php-email-form/validate.js"></script>
        <script src="../vendor/aos/aos.js"></script>
        <script src="../vendor/typed.js/typed.umd.js"></script>
        <script src="../vendor/purecounter/purecounter_vanilla.js"></script>
        <script src="../vendor/waypoints/noframework.waypoints.js"></script>
        <script src="../vendor/glightbox/js/glightbox.min.js"></script>
        <script src="../vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
        <script src="../vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="../vendor/swiper/swiper-bundle.min.js"></script>

        <!-- Main JS File -->
        <script src="../resources/js/main.js"></script>

    </body>

</html>
