<aside id="sidebar" class="sidebar bg-dark">
    <ul class="sidebar-nav" id="sidebar-nav">
        <!-- /* apartado de página principal */ -->
        <li class="nav-item">
            <Link class="nav-link collapsed" to="/home">
                <i class="bi bi-grid"></i>
                <span>Inicio</span>
            </Link>
        </li>

        <!-- apartado de menú de servicios -->
        <li class="nav-item">
            <Link to="/enterprise" class="nav-link collapsed">
                <i class="bi bi-building"></i>
                <span> Gestión de Empresas</span>
            </Link>
        </li>
        
        <li class="nav-item">
            <a href="gestion_servicios.php" class="nav-link collapsed">
                <i class="bi bi-box-seam"></i>
                <span> Planes</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="gestion_servicios.php" class="nav-link collapsed">
                <i class="bi bi-calendar"></i>
                <span> Mensualidades</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="gestion_servicios.php" class="nav-link collapsed">
                <i class="bi bi-currency-dollar"></i>
                <span> Pagos</span>
            </a>
        </li>

        <li class="nav-item">
            <Link to="/user" class="nav-link collapsed">
                <i class="bi bi-person-circle"></i>
                <span> Usuarios</span>
            </Link>
        </li>

        <!-- apartado de gestión de usuarios -->
        <li class="nav-item">
            <Link to="/rol" class="nav-link collapsed">
                <i class="bi bi-person-plus"></i>
                <span>Roles</span>
            </Link>
        </li>

        <!-- apartado del perfil de usuario -->
        <li class="nav-item">
            <Link to="profile" class="nav-link collapsed">
                <i class="bi bi-person"></i>
                <span>Mi perfil</span>
            </Link>
        </li>

        <!-- apartado de configuración de sistema -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#setting-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear"></i>
                <span>Configuración</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="setting-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="./configuracion.php">
                        <i class="bi bi-circle"></i>
                        <span>Ajustes del sistema</span>
                    </a>
                </li>

                <li>
                    <a href="./bitacora.php">
                        <i class="bi bi-circle"></i>
                        <span>Bitácora</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- apartado de ayuda -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#ayuda-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-question-circle"></i>
                <span>Ayuda</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="ayuda-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="../vista/manuales/MANUAL_DE_USUARIO_CHINITA.pdf" target="_blank" rel="noreferrer">
                        <i class="bi bi-circle"></i>
                        <span>Manual de usuario</span>
                    </a>
                </li>

                <li>
                    <a href="./manuales/MANUAL_DE_INSTALACION_CHINITA.pdf" target="_blank" rel="noreferrer">
                        <i class="bi bi-circle"></i>
                        <span>Manual de instalación</span>
                    </a>
                </li>
                <li>
                    <a href="./manuales/MANUAL_DE_SISTEMA_CHINITA.pdf" target="_blank" rel="noreferrer">
                        <i class="bi bi-circle"></i>
                        <span>Manual de sistema</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <button class="nav-link collapsed btn-exit-system">
                <i class="bi bi-box-arrow-right"></i>
                <span>Cerrar sesión</span>
            </button>
        </li>
    </ul>
</aside>