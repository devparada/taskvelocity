<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | Proyecto <?php echo $proyecto["nombre_proyecto"]; ?></title>
        <!-- Estilos propios -->  
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectosVer.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasProyectosVer.css">
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
            <h1 class="apartados"> <?php echo $proyecto["nombre_proyecto"] ?></h1>
            <div class="proyectos">
                <?php
                $idProyecto = $proyecto["id_proyecto"];
                (file_exists("./assets/img/proyectos/proyecto-$idProyecto.png")) ? $extension = "png" : $extension = "jpg";
                if (file_exists("./assets/img/proyectos/proyecto-$idProyecto.$extension")) {
                    ?>
                    <img src="/assets/img/proyectos/proyecto-<?php echo $proyecto["id_proyecto"] ?>" class="imagen-proyecto-tarea" alt="Imagen Proyecto <?php echo $proyecto["nombre_proyecto"] ?>">
                <?php } ?>
                <div class="informacion-proyecto">
                    <p>Descripción del proyecto: </p>
                    <p><?php echo ($proyecto["descripcion_proyecto"] == "") ? "No tiene descripción" : $proyecto["descripcion_proyecto"] ?></p>
                    <p>Fecha límite: <?php echo isset($proyecto["fecha_limite_proyecto"]) ? $proyecto["fecha_limite_proyecto"] : "No tiene fecha límite" ?></p>
                    <?php if (!empty($tareas)) { ?>
                        <table id="tabla-tareas" border="1">
                            <thead>
                            <caption>Tareas del proyecto</caption>
                            <th>Nombre</th>
                            <th>Fecha límite</th>
                            <th>Etiqueta</th>
                            </thead>
                            <?php foreach ($tareas as $t) { ?>
                                <tr>
                                    <td><a href="/tareas/ver/<?php echo $t["id_tarea"] ?>" class="enlace-ir-tarea"><?php echo $t["nombre_tarea"] ?> <i class="fas fa-external-link-alt"></i></a></td>
                                    <td><?php echo $t["fecha_limite_tarea"] ?? "No tiene" ?></td>
                                    <td><?php echo $t["nombre_etiqueta"] ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else { ?>
                        <p>No hay tareas asociadas a este proyecto</p>
                    <?php } ?>
                    <p>Miembros: <?php
                        foreach ($usuarios as $u) {
                            echo "<img src='/assets/img/usuarios/avatar-" . $u["id_usuario"] . "' class='imagen-perfil-pequena'>" . $u["username"] . " ";
                        }
                        ?></p>
                    <p>Propietario: <?php echo isset($proyecto["id_usuario_proyecto_prop"]) && ($proyecto["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $proyecto["username"] ?></p>
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
        </main> <!-- Continua en plantillas/footer -->
