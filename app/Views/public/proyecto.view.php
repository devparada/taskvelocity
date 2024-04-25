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
        <p id="info">(primera version mejorada)</p>
        <?php foreach ($proyectos as $p) { ?>
            <div class="proyectos">
                <p>Nombre del proyecto: <?php echo $p["nombre_proyecto"] ?></p>
                <p>Fecha límite: <?php echo $p["fecha_limite_proyecto"] ?></p>
                <p>Propietario: <?php echo isset($p["id_usuario_proyecto_prop"]) && ($p["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú eres el propietario" : $p["id_usuario_proyecto_prop"] ?></p>
                <a href="/proyectos/ver/<?php echo $p["id_proyecto"] ?>" class="botones">Entrar en el proyecto</a>
            </div>
        <?php } ?>
        <div id="perfil">
        <p>Sesión inciada como: <?php echo $_SESSION["usuario"]["username"]; ?></p>
        <a href="/logout" class="botones">Cerrar sesión</a>
        </div>
    </body>
</html>
