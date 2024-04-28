<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>TaskVelocity | Proyecto <?php echo $proyecto["nombre_proyecto"]; ?></title>
        <link rel="stylesheet" href="../../assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="../../assets/css/public/estilosProyectos.css">
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/">
                    <img src="../../../assets/img/logo.png" alt="Logo" class="imagenes-pequeñas">
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
                    <img src="/assets/img/users/avatar-<?php echo $_SESSION["usuario"]["id_usuario"] ?>" alt="Avatar usuario <?php echo $_SESSION["usuario"]["username"] ?>">
                    <p><?php echo $_SESSION["usuario"]["username"] ?></p>
                </div>
            <?php } else { ?>
                <a href="/login" class="botones">Iniciar sesión</a>
            <?php } ?>
        </header>
        <main>
            <h1>Proyecto <?php echo $proyecto["nombre_proyecto"] ?></h1>
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
        </main>
        <footer>
            <div>
                <p>TaskVelocity - 2024</p>
            </div>
            <div id="iconos-footer">
                <a href="#">Linkedin</a>
                <a href="#">Youtube</a>
                <a href="#">Twitter / X</a>
                <a href="#">Instagram</a>
            </div>
        </footer>
    </body>
</html>
