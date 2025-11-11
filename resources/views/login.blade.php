<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="icon" type="image/svg+xml" href="./siscopam.png" />

        <title>SISCOPAM</title>

        <link rel="stylesheet" href="../resources/css/style.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

        <script type="text/javascript">

            function dataTable(classTable = "example"){

                var t = $(`.${classTable}`).DataTable( {
                    language: {
                        url: './src/assets/js/dataTables-Español.json'
                    },
                    lengthMenu: [[5, 10, 15, 20, 25, 50, 100, -1], [5, 10, 15, 20, 25, 50, 100, "Todos"]],
                    responsive: true,
                } );

                t.on( 'order.dt search.dt', function () {
                    let i = 1;
                    t.cells(null, 0, {search:'applied', order:'applied'}).every( function (cell) {
                        this.data(i++);
                    } );
                } ).draw();
            }

            setTimeout(() => {
                dataTable("DataTable");
            }, 5000);

        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    </head>
    <body data-bs-theme="dark" class="bg-dark-subtle">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="#">

                    <img src="./siscopam.png" alt="SisCoPaM Logo" height="30" class="d-inline-block align-top me-2" />
                    SisCoPaM
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Sobre Nostros</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Funcionalidades</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Plans y Precios</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
                        <li class="nav-item"><a class="btn btn-warning ms-lg-3" href="#">Registrarse</a></li>
                    </ul>
                </div>
            </div>
        </nav>


        <header class="hero-section">
            <div class="container hero-content">
                <h1 class="text-white" style="text-wrap: balance">Simplifica la Gestión de Pagos y Mensualidades de tu Gimansio con SisCoPaM</h1>
                <p class="lead mb-4" style="text-wrap: balance">Control total, notificaciones automáticas y seguridad de datos para los administradores de gimnasios.</p>
                <div>
                    <a href="#" class="btn btn-warning">Descubre Más</a>
                    <a href="#" class="btn btn-outline-light">Ver Demo</a>
                </div>
            </div>
        </header>

        <section class="py-5 sectionReview">
            <div class="container">
                <div class="row row-cols-1 row-cols-md-3 g-4">

                    <div class="col">
                        <div class="feature-box">
                            <h3 class="">Rescistra Denios</h3>
                            <p>Soben hotras</p>
                            <p>Mesmasa de lvefllatos</p>
                            <p>Mensnada de al gntilos</p>
                            <p>Giumasio de ale slasitos</p>
                            <p>Resorate del <a href="#">Coccuetlado reipiero</a></p>
                            <p>For cicial de ramión es <a href="#">ginnsos</a></p>
                        </div>
                    </div>

                    <div class="col">
                        <div class="feature-box">
                            <h3 class="">Doogla Secios</h3>
                            <p>Aoomtlicland a pimasios</p>
                            <p>Masmasa de sl Gistlada</p>
                            <p>Mensucia de de ten comtos</p>
                            <p>Ensdindón cbmet Goins tatenos</p>
                            <p>Lagenciacion de atraccusior de daes</p>
                            <p>sepon as astre peiit an <a href="#">frorrr@cleaniloss</a> ginasios</p>
                        </div>
                    </div>

                    <div class="col">
                        <div class="feature-box">
                            <h3 class="">Resistras Docíos</h3>
                            <p>Sobra Notas</p>
                            <p>Mesruatono de lvefialds</p>
                            <p>Mencnela de del alndialos</p>
                            <p>Contrición eora expinpbends y datos</p>
                            <p><a href="#">Toccicasiestión cderslantienllos</a></p>
                            <p><a href="#">Fese Tawlerts Gertos</a> ginasios</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <footer class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-5 my-5 border-top">
                <div class="col mb-3">
                    <a href="/" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none" aria-label="Bootstrap">
                        <svg class="bi me-2" width="40" height="32" aria-hidden="true">
                            <use xlink:href="#bootstrap"></use>
                        </svg>
                    </a>
                    <p class="text-body-secondary">© 2025</p>
                </div>
                <div class="col mb-3"></div>
                <div class="col mb-3">
                    <h5>Section</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">Home</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">Features</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">Pricing</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">FAQs</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">About</a>
                        </li>
                    </ul>
                </div>
                <div class="col mb-3">
                    <h5>Section</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">Home</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">Features</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">Pricing</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">FAQs</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">About</a>
                        </li>
                    </ul>
                </div>
                <div class="col mb-3">
                    <h5>Section</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">Home</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">Features</a>
                        </li> <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">Pricing</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">FAQs</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="#" class="nav-link p-0 text-body-secondary">About</a>
                        </li>
                    </ul>
                </div>
            </footer>
        </div>
    </body>
</html>
