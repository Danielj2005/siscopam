
<details class="group">
    <!-- Encabezado / Botón disparador -->
    
    <summary class=" bg-[#f6f9ff] dark:bg-slate-800 justify-between dark:shadow-slate-300/80 dark:text-slate-300 gap-2 rounded-3 shadow-md flex items-center p-2 text-gray-700 hover:bg-gray-400 dark:hover:bg-slate-500 rounded-lg cursor-pointer transition-colors list-none [&::-webkit-details-marker]:hidden">
        <div class="flex items-center space-x-3">
        <i class="bi bi-box-seam-fill"></i>
        <span>Inventario</span>
        </div>
        <!-- Flecha que rota al abrir -->
        <i class="bi bi-chevron-down text-sm transition-transform duration-200 group-open:rotate-180"></i>
    </summary>

    <!-- Contenido colapsable -->
    <ul class="bg-[#f6f9ff] -translate-y-2 dark:bg-slate-800 dark:text-slate-300 delay-300 pb-2 pl-2 pt-2 rounded-bottom-3 shadow-md dark:shadow-slate-500 space-y-1 transition-all">
        
        <li class="hover:bg-slate-500/40 p-1 rounded-xl group">
            <a class="dark:text-white hover:text-slate-200 text-slate-800" href="./gestion_productos.php">
                <i class="bi bi-caret-right text-sm"></i>
                <span>Gestión de Productos</span>
            </a>
        </li>
        
        <li class="d-none hover:bg-slate-500/40 p-1 rounded-xl group">
            <a class="hover:text-slate-800 text-slate-800" href="./entrada_de_productos.php">
                <i class="bi bi-caret-right"></i>
                <span>Registro de Compras</span>
            </a>
        </li>

        <li class="d-none hover:bg-slate-500/40 p-1 rounded-xl group">
            <a class="hover:text-slate-800 text-slate-800" href="./proveedor.php">
                <i class="bi bi-caret-right"></i>
                <span>Gestión de Proveedores</span>
            </a>
        </li>
    </ul>
</details>