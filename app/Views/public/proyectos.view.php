<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | Tus proyectos</title>
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/"  class="logo-enlace">
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
            <?php if (isset($informacion)) { ?>
                <div class="alerta-<?php echo ($informacion["estado"] == "success" ? "success" : "danger") ?>">
                    <p><?php echo $informacion["texto"] ?></p>
                </div>
            <?php } ?>
            <div class="proyectos-grid">
                <?php foreach ($proyectos as $p) { ?>
                    <div class="proyectos">
                        <?php
                        $idProyecto = $p["id_proyecto"];
                        (file_exists("./assets/img/proyectos/proyecto-$idProyecto.png")) ? $extension = "png" : $extension = "jpg";
                        if (file_exists("./assets/img/proyectos/proyecto-$idProyecto.$extension")) {
                            ?>
                            <img src="/assets/img/proyectos/proyecto-<?php echo $p["id_proyecto"] ?>" alt="Imagen Proyecto <?php echo $p["nombre_proyecto"] ?>" class="imagen-proyecto">        
                        <?php } ?>
                        <div class="informacion-proyecto">
                            <p>Nombre del proyecto: <?php echo $p["nombre_proyecto"] ?></p>
                            <?php if ($p["editable"] == 1) { ?>
                                <p>Fecha límite: <?php echo isset($p["fecha_limite_proyecto"]) ? $p["fecha_limite_proyecto"] : "No tiene fecha límite" ?></p>
                                <p>Propietario: <?php echo isset($p["id_usuario_proyecto_prop"]) && ($p["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $p["id_usuario_proyecto_prop"] ?></p>
                                <div class="botones-proyecto">
                                    <a href="/proyectos/editar/<?php echo $p["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                                    <a href="/proyectos/borrar/<?php echo $p["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                                <?php } else { ?>
                                    <p id="texto-personal">Este es tu proyecto personal no lo puedes borrar</p>
                                    <div class="botones-proyecto">
                                    <?php } ?>
                                    <a href="/proyectos/ver/<?php echo $p["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-expand"></i> Más detalles</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
        </main> <!-- Continua en plantillas/footer -->
