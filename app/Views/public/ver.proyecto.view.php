<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>TaskVelocity | Proyecto <?php echo $proyecto["nombre_proyecto"]; ?></title>
        <link rel="stylesheet" href="../../assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="../../assets/css/public/estilosProyectos.css">
    </head>
    <body>
        <h1>Proyecto <?php echo $proyecto["nombre_proyecto"] ?></h1>
        <p id="info">(primera version)</p>
        <div class="proyectos">
            <p>Nombre del proyecto: <?php echo $proyecto["nombre_proyecto"] ?></p>
            <p>Descripción del proyecto: <?php echo $proyecto["descripcion_proyecto"] ?></p>
            <p>Fecha límite: <?php echo $proyecto["fecha_limite_proyecto"] ?></p>
            <p>Tareas:</p>
            <?php if (!empty($tareas)) { ?>
                <ul>
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
                foreach ($miembros as $u) {
                    echo $u["username"] . " ";
                }
                ?></p>
            <p>Propietario: <?php echo isset($proyecto["id_usuario_proyecto_prop"]) && ($proyecto["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú eres el propietario" : $proyecto["id_usuario_proyecto_prop"] ?></p>
            <a href="/proyectos" class="botones">Volver a proyectos</a>
        </div>
        <div id="perfil">
            <p>Sesión inciada como: <?php echo $_SESSION["usuario"]["username"]; ?></p>
            <a href="/logout" class="botones">Cerrar sesión</a>
        </div>
    </body>
</html>
