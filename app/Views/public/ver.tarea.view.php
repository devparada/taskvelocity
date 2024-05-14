<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | Tarea <?php echo $tarea["nombre_tarea"]; ?></title>
        <!-- Estilos propios -->  
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasVer.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasProyectosVer.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->  
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/locale/es.js"></script>
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
            <div id="perfil-cerrar">
                <div id="perfil">
                    <a href="/perfil/<?php echo $_SESSION["usuario"]["id_usuario"] ?>" class="enlace-perfil">
                        <img src="/assets/img/usuarios/avatar-<?php echo $_SESSION["usuario"]["id_usuario"] ?>" alt="Avatar usuario <?php echo $_SESSION["usuario"]["username"] ?>">
                        <p><?php echo $_SESSION["usuario"]["username"] ?></p>
                    </a>
                </div>
                <a href="/logout" class="botones"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</a>
            </div>
        </header>
        <main>
            <h1 class="apartados">Tarea <?php echo $tarea["nombre_tarea"] ?></h1>
            <div class="proyectos" id="informacion">
                <?php
                $idTarea = $tarea["id_tarea"];
                (file_exists("./assets/img/tareas/tarea-$idTarea.png")) ? $extension = "png" : $extension = "jpg";
                if (file_exists("./assets/img/tareas/tarea-$idTarea.$extension")) {
                    ?>
                    <img src="/assets/img/tareas/tarea-<?php echo $tarea["id_tarea"] ?>" class="imagen-proyecto-tarea" alt="Imagen Tarea <?php echo $tarea["nombre_tarea"] ?>">
                <?php } ?>
                <div class="informacion-proyecto">
                    <p>Nombre de la tarea: <?php echo $tarea["nombre_tarea"] ?></p>
                    <p>Descripción de la tarea: <?php echo ($tarea["descripcion_tarea"] == "") ? "No tiene descripción" : "" ?></p>
                    <p id="fecha-limite">Fecha límite: <?php echo isset($tarea["fecha_limite_tarea"]) ? $tarea["fecha_limite_tarea"] : "No tiene fecha límite" ?></p>
                    <p>Proyecto: <a href="/proyectos/ver/<?php echo $tarea["id_proyecto"] ?>" id="boton-ir-proyecto"><?php echo isset($tarea["id_proyecto"]) ? $tarea["nombre_proyecto"] : "" ?> <i class="fas fa-external-link-alt"></i></a></p>
                    <p>Color: <span style="background-color: <?php echo $tarea["valor_color"] ?>" class="color-circulo"></span><?php echo $tarea["nombre_color"] ?></p>
                    <p>Miembros: <?php
                        foreach ($usuarios as $u) {
                            echo "<img src='/assets/img/usuarios/avatar-" . $u["id_usuario"] . "' class='imagen-perfil-pequena'>" . $u["username"] . " ";
                        }
                        ?></p>
                    <p>Propietario: <?php echo isset($tarea["id_usuario_tarea_prop"]) && ($tarea["id_usuario_tarea_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $tarea["username"] ?></p>
                    <div class="botones-proyecto">
                        <a href="/tareas" class="botones"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                        <a href="/tareas/borrar/<?php echo $tarea["id_tarea"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                        <a href="/tareas/editar/<?php echo $tarea["id_tarea"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                    </div>
                </div>
            </div>

            <script>
                moment.locale('es');
                if (moment([document.getElementById("fecha-limite").innerText], "YYYY-MM-DD").fromNow() !== "Fecha inválida") {
                    document.getElementById("fecha-limite").innerHTML = "Fecha límite: " + moment([document.getElementById("fecha-limite").innerText], "YYYY-MM-DD").fromNow();
                } else {
                    document.getElementById("fecha-limite").innerHTML = "Fecha límite: No tiene fecha límite";
                }
            </script>
        </main> <!-- Continua en plantillas/footer -->
