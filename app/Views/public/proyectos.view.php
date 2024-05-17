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
        <!-- Moment -->
        <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/locale/es.js"></script>
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
                    <li><a href="/proyectos">Proyectos</a></li>
                    <li><a href="/tareas">Tareas</a></li>
                    <li><a href="/contacto">Contacto</a></li>
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
                <h1>Tus proyectos</h1>
                <a href="/proyectos/crear" class="botones"><i class="fa-solid fa-circle-plus"></i> Crear un proyecto</a>
            </div>
            <?php if (isset($informacion)) { ?>
                <div class="alerta-<?php echo ($informacion["estado"] == "success" ? "success" : "danger") ?>">
                    <p><?php echo $informacion["texto"] ?></p>
                </div>
            <?php } ?>
            <div class="proyectos-grid">
                <?php foreach ($proyectos as $p) { ?>
                    <div class="proyectos" id="<?php echo $p["id_proyecto"] ?>">
                        <?php
                        $idProyecto = $p["id_proyecto"];
                        if (file_exists("./assets/img/proyectos/proyecto-$idProyecto")) {
                            ?>
                            <img src="/assets/img/proyectos/proyecto-<?php echo $p["id_proyecto"] ?>" alt="Imagen Proyecto <?php echo $p["nombre_proyecto"] ?>" class="imagen-proyecto">        
                        <?php } ?>
                        <div class="informacion-proyecto">
                            <h3>Proyecto: <?php echo $p["nombre_proyecto"] ?></h3>
                            <?php if ($p["editable"] == 1) { ?>
                                <p class="fecha-limite"><?php echo $p["fecha_limite_proyecto"] ?></p>
                                <p>Tareas: <?php echo (!empty($p["tareas"])) ? count($p["tareas"]) : "0" ?></p>
                                <p>Propietario: <?php echo isset($p["id_usuario_proyecto_prop"]) && ($p["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $p["username"] ?></p>
                                <div class="botones-proyecto">
                                    <a href="/proyectos/editar/<?php echo $p["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                                    <a href="/proyectos/borrar/<?php echo $p["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                                <?php } else { ?>
                                    <p id="texto-personal">Este es tu proyecto personal se usa por defecto y no se puede borrar</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <script src="assets/js/public/fechasTareasProyectos.js"></script>
            <script>
                const proyectos = document.getElementsByClassName("proyectos");

                for (var i = 0; i < proyectos.length; i++) {
                    (function (i) {
                        // Al hacer click en el proyecto va a la siguiente url
                        proyectos[i].addEventListener("click", function () {
                            window.location.href = "/proyectos/ver/" + proyectos[i].id;
                        });
                    })(i);
                }
            </script>
        </main> <!-- Continua en plantillas/footer -->
