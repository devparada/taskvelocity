<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | Tus tareas</title>
        <!-- Estilos propios -->  
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareas.css">
        <!-- Iconos -->
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
                <h1>Tus tareas</h1>
                <a href="/tareas/crear" class="botones">Crear una tarea</a>
            </div>
            <?php if (isset($informacion)) { ?>
                <div class="alerta-<?php echo ($informacion["estado"] == "success" ? "success" : "danger") ?>">
                    <p><?php echo $informacion["texto"] ?></p>
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
                            <p>Fecha límite: <?php echo isset($t["fecha_limite"]) ? $t["fecha_limite"] : "No tiene fecha límite" ?></p>
                            <p>Propietario: <?php echo isset($t["id_usuario_tarea_prop"]) && ($t["id_usuario_tarea_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú eres el propietario" : $t["username"] ?></p>
                            <a href="/tareas/ver/<?php echo $t["id_tarea"] ?>" class="botones">Ver</a>
                            <a href="/tareas/editar/<?php echo $t["id_tarea"] ?>" class="botones">Editar</a>
                            <a href="/tareas/borrar/<?php echo $t["id_tarea"] ?>" class="botones">Borrar</a>
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
                <a href="/" class="logo-enlace"><img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequeñas">
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
