<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | Tus tareas</title>
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareas.css">
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
            <div id="introduccion">
                <h1>Tus tareas</h1>
                <div>
                    <?php foreach ($etiquetas as $etiqueta) { ?>
                        <a class="botones botones-filtros" href="/tareas?etiqueta=<?php echo $etiqueta["id_etiqueta"] ?>"><?php echo $etiqueta["nombre_etiqueta"] ?></a>
                    <?php } ?>
                    <a class="botones botones-filtros" href="/tareas">Mostrar todas</a>
                </div>
                <a href="/tareas/crear" class="botones">Crear una tarea</a>
            </div>
            <?php if (isset($informacion)) { ?>
                <div class="alerta-<?php echo ($informacion["estado"] == "success" ? "success" : "danger") ?>">
                    <p><?php echo $informacion["texto"] ?></p>
                </div>
            <?php } ?>
            <?php if (empty($tareas) && $_SERVER["REQUEST_URI"] == "/tareas") { ?>
                <div class="informacion">
                    <p><i class="fa-solid fa-circle-info"></i> Crea tu primera tarea pulsando en el botón Crear una tarea</p>
                </div>
            <?php } else if ((empty($tareas)) && $_SERVER["REQUEST_URI"] != "/tareas") { ?>
                <div class="informacion informacion-warning">
                    <p><i class="fa-solid fa-circle-info"></i> No se han encontrado tareas con esta etiqueta</p>
                </div>
            <?php } ?>
            <div id="tareas-grid">
                <?php foreach ($tareas as $t) { ?>
                    <div class="tareas" style="background-color:<?php echo $t["valor_color"] ?>">
                        <?php
                        $idTarea = $t["id_tarea"];
                        (file_exists("./assets/img/tarea-$idTarea.png")) ? $extension = "png" : $extension = "jpg";
                        if (file_exists("./assets/img/tareas/tarea-$idTarea.$extension")) {
                            ?>
                            <img src="/assets/img/tareas/tarea-<?php echo $t["id_tarea"] ?>.jpg" alt="Imagen Tarea <?php echo $t["nombre_tarea"] ?>" class="imagen-proyecto">        
                        <?php } ?>
                        <div class="informacion-tarea">
                            <p>Nombre de la tarea: <?php echo $t["nombre_tarea"] ?></p>
                            <p>Etiqueta: <?php echo $t["nombre_etiqueta"] ?></p>
                            <p class="fecha-limite"><?php echo $t["fecha_limite_tarea"] ?></p>
                            <p>Propietario: <?php echo isset($t["id_usuario_tarea_prop"]) && ($t["id_usuario_tarea_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $t["username"] ?></p>
                            <p>Proyecto: <?php echo $t["nombre_proyecto"] ?></p>
                            <div class="botones-tareas">    
                                <a href="/tareas/editar/<?php echo $t["id_tarea"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                                <a href="/tareas/borrar/<?php echo $t["id_tarea"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                                <a href="/tareas/ver/<?php echo $t["id_tarea"] ?>" class="botones"><i class="fa-solid fa-expand"></i> Ver</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <script>
            moment.locale('es');
            for (var i = 0; i < document.getElementsByClassName("fecha-limite").length; i++) {
                if (moment([document.getElementsByClassName("fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow() !== "Fecha inválida") {
                    document.getElementsByClassName("fecha-limite")[i].innerHTML = "Fecha límite: " + moment([document.getElementsByClassName("fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow();
                } else {
                    document.getElementsByClassName("fecha-limite")[i].innerHTML = "Fecha límite: No tiene fecha límite";
                }
            }
        </script>
    </main> <!-- Continua en plantillas/footer -->
