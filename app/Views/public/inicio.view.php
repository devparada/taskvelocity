<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | Inicio</title>
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosInicio.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/" class="logo-enlace">
                    <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
                    <h2>TaskVelocity</h2>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="/proyectos" class="botones">Proyectos</a></li>
                    <li><a href="/tareas" class="botones">Tareas</a></li>
                    <li><a href="/contacto" class="botones">Contacto</a></li>
                </ul>
            </nav>
            <?php if (isset($_SESSION["usuario"])) { ?>
                <div id="perfil-cerrar">
                    <div id="perfil">
                        <a href="/perfil/<?php echo $_SESSION["usuario"]["id_usuario"] ?>" class="enlace-perfil">
                            <img src="/assets/img/usuarios/avatar-<?php echo $_SESSION["usuario"]["id_usuario"] ?>" alt="Avatar usuario <?php echo $_SESSION["usuario"]["username"] ?>">
                            <p><?php echo $_SESSION["usuario"]["username"] ?></p>
                        </a>
                    </div>
                    <a href="/logout" class="botones"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</a>
                </div>
            <?php } else { ?>
                <a href="/login" class="botones">Iniciar sesión</a>
            <?php } ?>
        </header>
        <main>
            <div id="main-introduccion">
                <h1 class="apartados apartados-inicio">TaskVelocity</h1>
                <p>TaskVelocity es una aplicación web que es un gestor de tareas y proyectos veloz.</p>
                <p>Con solo crear una cuenta tienes acceso a este gestor de tareas y proyectos.</p>
            </div>
            <div id="div-caracteristicas">
                <h2 class="apartados apartados-inicio">Características</h2>
                <p>Tiene las siguientes caracteristicas:</p>
                <div id="main-caracteristicas">
                    <div class="imagen-caracteristicas">
                        <img src="assets/img/ejemplo-proyectos.png" alt="Ejemplo Proyectos"></img>
                    </div>
                    <div id="caracteristica-proyectos" class="caracteristicas-texto">
                        <p>Un gestor de proyectos veloz donde ver todoslos proyectos, 
                            añadir una fecha límite y una imagen y añadir más miembros para trabajar en compañia</p>
                    </div>
                    <div id="caracteristica-tareas" class="caracteristicas-texto">
                        <p>Un gestor de tareas que pueden tener distinto color las tareas según lo prefiera el usuario, una fecha límite, un proyecto asociado
                            y una imagen y añadir a más personas para trabjar juntos</p>
                    </div>
                    <div class="imagen-caracteristicas">
                        <img src="assets/img/ejemplo-tareas.png" alt="Ejemplo Tareas"></img>
                    </div>

                    <div class="imagen-caracteristicas">
                        <img src="assets/img/ejemplo-proyectos.png" alt="Ejemplo Perfil"></img>
                    </div>
                    <div id="caracteristica-perfil" class="caracteristicas-texto">
                        <p>También puedes editar tu perfil a tu gusto como por ejemplo cambiando el color favorito, la imagen y la descripción (en construcción)</p>
                    </div>
                </div>
            </div>
        </main> <!-- Continua en plantillas/footer -->
