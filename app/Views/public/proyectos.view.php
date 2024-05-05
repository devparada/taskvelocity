<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>TaskVelocity | Tus proyectos</title>
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/">
                    <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequeñas">
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
                    <img src="/assets/img/usuarios/avatar-<?php echo $_SESSION["usuario"]["id_usuario"] ?>" alt="Avatar usuario <?php echo $_SESSION["usuario"]["username"] ?>">
                    <p><?php echo $_SESSION["usuario"]["username"] ?></p>
                    <a href="/logout" class="botones"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</a>
                </div>
            <?php } else { ?>
                <a href="/login" class="botones">Iniciar sesión</a>
            <?php } ?>
        </header>
        <main>
            <div id="introduccion">
                <h1>Tus proyectos</h1>
                <a href="/proyectos/crear" class="botones">Crear un proyecto</a>
            </div>
            <div class="proyectos-grid">
                <?php foreach ($proyectos as $p) { ?>
                    <div class="proyectos">
                        <?php
                        $idProyecto = $p["id_proyecto"];
                        if (file_exists("./assets/img/proyectos/proyecto-$idProyecto.png")) {
                            ?>
                            <img src="/assets/img/proyectos/proyecto-<?php echo $p["id_proyecto"] ?>" alt="Imagen Proyecto <?php echo $p["nombre_proyecto"] ?>" class="imagen-proyecto">        
                        <?php } ?>
                        <div class="informacion-proyecto">
                            <p>Nombre del proyecto: <?php echo $p["nombre_proyecto"] ?></p>
                            <p>Fecha límite: <?php echo $p["fecha_limite_proyecto"] ?></p>
                            <p>Propietario: <?php echo isset($p["id_usuario_proyecto_prop"]) && ($p["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú eres el propietario" : $p["id_usuario_proyecto_prop"] ?></p>
                            <div class="botones-proyecto">
                                <a href="/proyectos/ver/<?php echo $p["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-expand"></i> Más detalles</a>
                                <a href="/proyectos/editar/<?php echo $p["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                                <a href="/proyectos/borrar/<?php echo $p["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </main>
        <footer>
            <div>
                <p>Proyecto de Fin de Ciclo Superior DAW 2024</p>
            </div>
            <div id="logo-footer">
                <a href="/" class="logo-enlace"><img src="../assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequeñas">
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
