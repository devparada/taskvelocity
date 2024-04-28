<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>TaskVelocity | Inicio</title>
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosInicio.css">
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/">
                    <img src="../assets/img/logo.png" alt="Logo" class="imagenes-pequeñas">
                </a>
                <h2>TaskVelocity</h2>
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
                    <img src="/assets/img/users/avatar-<?php echo $_SESSION["usuario"]["id_usuario"] ?>" alt="Avatar usuario <?php echo $_SESSION["usuario"]["username"] ?>">
                    <p><?php echo $_SESSION["usuario"]["username"] ?></p>
                    <a href="/proyectos" class="botones">Ir a tus proyectos</a>
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
                    <div class="imagen-proyectos">
                        <img src="assets/img/caracteristicas-1.png"></img>
                    </div>
                    <div id="caracteristica-proyectos" class="caracteristicas-texto">
                        <p>Un gestor de proyectos veloz donde ver todas las tareas asociadas a un proyecto, 
                            añadir una fecha límite y una imagen y añadir más miembros para trabajar en compañia</p>
                    </div>
                    <div id="caracteristica-proyectos" class="caracteristicas-texto">
                        <p>Un gestor de tareas que pueden tener distinto color según lo prefiera el usuario, una fecha límite y una imagen y añadir 
                            a más personas para trabjar juntos</p>
                    </div>
                    <div class="imagen-proyectos">
                        <img src="assets/img/caracteristicas-1.png"></img>
                    </div>

                    <div class="imagen-proyectos">
                        <img src="assets/img/caracteristicas-1.png"></img>
                    </div>
                    <div id="caracteristica-proyectos" class="caracteristicas-texto">
                        <p>También puedes editar tu perfil a tu gusto como por ejemplo cambiando el color favorito, la imagen y la descripción</p>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <div>
                <p>TaskVelocity - 2024</p>
            </div>
            <div id="iconos-footer">
                <a href="#">Linkedin</a>
                <a href="#">Youtube</a>
                <a href="#">Twitter / X</a>
                <a href="#">Instagram</a>
            </div>
        </footer>
    </body>
</html>
