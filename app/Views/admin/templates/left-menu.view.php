<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="/admin" class="nav-link <?php echo isset($seccion) && $seccion === '/admin' ? 'active' : ''; ?>">
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
                    <a href="/admin/usuarios" class="nav-link <?php echo isset($seccion) && $seccion === '/admin/usuarios' ? 'active' : ''; ?>">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Usuarios del Sistema</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/admin/tareas" class="nav-link <?php echo isset($seccion) && $seccion === '/admin/tareas' ? 'active' : ''; ?>">
                        <i class="fas fa-tasks nav-icon"></i>
                        <p>Tareas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/admin/proyectos" class="nav-link <?php echo isset($seccion) && $seccion === '/admin/proyectos' ? 'active' : ''; ?>">
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