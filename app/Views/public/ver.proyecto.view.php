<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | Proyecto <?php echo $proyecto["nombre_proyecto"]; ?></title>
        <!-- Estilos propios -->  
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasProyectosVer.css">
        <!-- Iconos -->  
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/">
                    <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
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
            <h1 class="apartados">Proyecto <?php echo $proyecto["nombre_proyecto"] ?></h1>
            <div class="proyectos">
                <?php
                $idProyecto = $proyecto["id_proyecto"];
                (file_exists("./assets/img/proyectos/proyecto-$idProyecto.png")) ? $extension = "png" : $extension = "jpg";
                if (file_exists("./assets/img/proyectos/proyecto-$idProyecto.$extension")) {
                    ?>
                    <img src="/assets/img/proyectos/proyecto-<?php echo $proyecto["id_proyecto"] ?>" class="imagen-proyecto" alt="Imagen Proyecto <?php echo $proyecto["nombre_proyecto"] ?>">
                <?php } ?>
                <div class="informacion-proyecto">
                    <p>Nombre del proyecto: <?php echo $proyecto["nombre_proyecto"] ?></p>
                    <p>Descripción del proyecto: <?php echo ($proyecto["descripcion_proyecto"] == "") ? "No tiene descripción" : "" ?></p>
                    <p>Fecha límite: <?php echo isset($proyecto["fecha_limite_proyecto"]) ? $proyecto["fecha_limite_proyecto"] : "No tiene fecha límite" ?></p>
                    <p>Tareas:</p>
                    <?php if (!empty($tareas)) { ?>
                    <ul class="lista-tareas">
                            <?php foreach ($tareas as $t) { ?>
                                <div>
                                    <li><?php echo $t["nombre_tarea"] ?></li>
                                </div>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <p>No hay tareas asociadas a este proyecto </p>
                    <?php } ?>
                    <p>Miembros: <?php
                        foreach ($usuarios as $u) {
                            echo "<img src='/assets/img/usuarios/avatar-" . $u["id_usuario"] . "' class='imagen-perfil-pequena'>" . $u["username"] . " ";
                        }
                        ?></p>
                    <p>Propietario: <?php echo isset($proyecto["id_usuario_proyecto_prop"]) && ($proyecto["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú eres el propietario" : $proyecto["id_usuario_proyecto_prop"] ?></p>
                    <?php if ($proyecto["editable"] == 1) { ?>
                        <div class="botones-proyecto">
                            <a href="/proyectos" class="botones"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                            <a href="/proyectos/borrar/<?php echo $proyecto["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                            <a href="/proyectos/editar/<?php echo $proyecto["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                        </div>
                    <?php } else { ?>
                        <div class="botones-proyecto">
                            <a href="/proyectos" class="botones"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </main>
        <footer>
            <div>
                <p>Proyecto de Fin de Ciclo Superior DAW 2024</p>
            </div>
            <div id="logo-footer">
                <a href="/" class="logo-enlace"><img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
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
