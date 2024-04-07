<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="/" class="nav-link active">
                <i class="nav-icon fas fa-th"></i>
                <p>Inicio</p>
            </a>
        </li>         

        <li class="nav-item menu-open">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-database"></i>
                <p>
                    Base de datos
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="/usuarios" class="nav-link <?php echo isset($seccion) && $seccion === '/usuarios' ? 'active' : ''; ?>">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Usuarios del Sistema</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/tareas" class="nav-link <?php echo isset($seccion) && $seccion === '/tareas' ? 'active' : ''; ?>">
                        <i class="fas fa-tasks nav-icon"></i>
                        <p>Tareas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/proyectos" class="nav-link <?php echo isset($seccion) && $seccion === '/proyectos' ? 'active' : ''; ?>">
                        <i class="fas fa-project-diagram nav-icon"></i>
                        <p>Proyectos</p>
                    </a>
                </li>
                <?php
                ?>

            </ul>
        </li>

    </ul>
</nav>
<!-- /.sidebar-menu -->