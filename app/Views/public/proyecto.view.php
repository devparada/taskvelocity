<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>TaskVelocity | Tus proyectos</title>
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
    </head>
    <body>
        <h1>Tus proyectos</h1>
        <p id="info">(primera version)</p>
        <?php foreach ($proyectos as $p) { ?>
            <div class="proyectos">
                <p>Nombre del proyecto: <?php echo $p["nombre_proyecto"] ?></p>
                <p>Fecha límite: <?php echo $p["fecha_limite_proyecto"] ?></p>
                <p>Propietario: <?php echo isset($p["id_usuario_proyecto_prop"]) && ($p["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú eres el propietario" : $p["id_usuario_proyecto_prop"] ?></p>
                <a href="/proyectos/view/<?php echo $p["id_proyecto"] ?>">Ver más información del proyecto</a>
            </div>
        <?php } ?>
        <a href="/logout">Cerrar sesión</a>
    </body>
</html>
