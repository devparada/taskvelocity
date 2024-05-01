<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>TaskVelocity | Inicio</title>
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosInicio.css">
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/" class="logo-enlace">
                    <img src="../assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequeñas">
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
                <div id="perfil">
                    <img src="/assets/img/usuarios/avatar-<?php echo $_SESSION["usuario"]["id_usuario"] ?>" alt="Avatar usuario <?php echo $_SESSION["usuario"]["username"] ?>">
                    <p><?php echo $_SESSION["usuario"]["username"] ?></p>
                    <a href="/logout" class="botones"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</a>
                </div>
            <?php } else { ?>
                <a href="/login" class="botones">Iniciar sesión</a>
            <?php } ?>
        </header>
        <main>
            <div id="main-introduccion">
                <h1 class="apartados">TaskVelocity</h1>
                <p>TaskVelocity es una aplicación web que es un gestor de tareas y proyectos veloz.</p>
                <p>Con solo crear una cuenta tienes acceso a este gestor de tareas y proyectos.</p>
            </div>
            <div id="div-caracteristicas">
                <h2 class="apartados">Características</h2>
                <p>Tiene las siguientes caracteristicas:</p>
                <div id="main-caracteristicas">
                    <div class="imagen-caracteristicas">
                        <img src="assets/img/ejemplo-proyectos.png" alt="Ejemplo Proyectos"></img>
                    </div>
                    <div id="caracteristica-proyectos" class="caracteristicas-texto">
                        <p>Un gestor de proyectos veloz donde ver todas las tareas asociadas a un proyecto, 
                            añadir una fecha límite y una imagen y añadir más miembros para trabajar en compañia</p>
                    </div>
                    <div id="caracteristica-tareas" class="caracteristicas-texto">
                        <p>Un gestor de tareas que pueden tener distinto color según lo prefiera el usuario, una fecha límite y una imagen y añadir 
                            a más personas para trabjar juntos</p>
                    </div>
                    <div class="imagen-caracteristicas">
                        <img src="assets/img/ejemplo-tareas.png" alt="Ejemplo Tareas"></img>
                    </div>

                    <div class="imagen-caracteristicas">
                        <img src="assets/img/ejemplo-proyectos.png" alt="Ejemplo Perfil"></img>
                    </div>
                    <div id="caracteristica-perfil" class="caracteristicas-texto">
                        <p>También puedes editar tu perfil a tu gusto como por ejemplo cambiando el color favorito, la imagen y la descripción</p>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <div>
                <p>Proyecto de Fin de Ciclo Superior DAW 2024</p>
            </div>
            <div id="logo-footer">
                <a href="#" class="logo-enlace"><img src="../assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequeñas">
                    <p>TaskVelocity</p></a>
            </div>
            <div id="iconos-footer">
                <a href="https://es.linkedin.com"><i class="fa-brands fa-linkedin"></i></a>
                <a href="https://www.youtube.com"><i class="fa-brands fa-youtube"></i></a>
                <a href="https://twitter.com"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="https://www.instagram.com"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </footer>
    </body>
</html>
