<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | Proyecto <?php echo $proyecto["nombre_proyecto"]; ?></title>
        <!-- Estilos propios -->  
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasVer.css">
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
            <h1 class="apartados">Tarea <?php echo $tarea["nombre_tarea"] ?></h1>
            <div class="proyectos">
                <?php
                $idTarea = $tarea["id_tarea"];
                (file_exists("./assets/img/tareas/tarea-$idTarea.png")) ? $extension = "png" : $extension = "jpg";
                if (file_exists("./assets/img/tareas/tarea-$idTarea.$extension")) {
                    ?>
                    <img src="/assets/img/tareas/tarea-<?php echo $tarea["id_tarea"] ?>" class="imagen-proyecto" alt="Imagen Proyecto <?php echo $tarea["nombre_tarea"] ?>">
                <?php } ?>
                <div class="informacion-proyecto">
                    <p>Nombre de la tarea: <?php echo $tarea["nombre_tarea"] ?></p>
                    <p>Descripción de la tarea: <?php echo ($tarea["descripcion_tarea"] == "") ? "No tiene descripción" : "" ?></p>
                    <p>Fecha límite: <?php echo isset($tarea["fecha_limite_tarea"]) ? $tarea["fecha_limite_proyecto"] : "No tiene fecha límite" ?></p>
                    <p>Proyecto: <a href="/proyectos/ver/<?php echo $tarea["id_proyecto"] ?>" id="boton-ir-proyecto"><?php echo isset($tarea["id_proyecto"]) ? $tarea["nombre_proyecto"] : "" ?> <i class="fas fa-external-link-alt"></i></a></p>
                    <p>Color: <?php echo $tarea["nombre_color"] ?></p>
                    <p>Miembros: <?php
                        foreach ($usuarios as $u) {
                            echo "<img src='/assets/img/usuarios/avatar-" . $u["id_usuario"] . "' class='imagen-perfil-pequena'>" . $u["username"] . " ";
                        }
                        ?></p>
                    <p>Propietario: <?php echo isset($tarea["id_usuario_tarea_prop"]) && ($tarea["id_usuario_tarea_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $proyecto["id_usuario_proyecto_prop"] ?></p>
                    <div class="botones-proyecto">
                        <a href="/tareas" class="botones"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                        <a href="/tareas/borrar/<?php echo $tarea["id_tarea"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                        <a href="/tareas/editar/<?php echo $tarea["id_tarea"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                    </div>
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
