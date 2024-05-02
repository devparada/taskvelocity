<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>TaskVelocity | Crear proyecto</title>
        <link rel="stylesheet" href="../../assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="../../assets/css/public/estilosProyectos.css">
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/" class="logo-enlace">
                    <img src="../../assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequeñas">
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
            <div class="formlario">
                <form action="<?php echo $seccion; ?>" method="post" enctype="multipart/form-data">         
                    <label for="nombre_proyecto">Nombre del proyecto *</label>
                    <input type="text" id="nombre_proyecto" name="nombre_proyecto" placeholder="Introduzca el nombre del proyecto" value="<?php echo isset($datos["nombre_proyecto"]) ? $datos["nombre_proyecto"] : "" ?>">

                    <label for="imagen_proyecto">Imagen</label>
                    <input type="file" id="imagen_proyecto" accept=".jpg,.png">

                    <label for="fecha_limite_proyecto">Fecha límite</label>
                    <input type="date" id="fecha_limite_proyecto" name="fecha_limite_proyecto" value="<?php echo isset($datos["fecha_limite_proyecto"]) ? $datos["fecha_limite_proyecto"] : "" ?>">

                    <label for="id_usuarios_asociados[]">Usuarios asociados *</label>
                    <select id="id_usuarios_asociados[]" name="id_usuarios_asociados[]" data-placeholder="Selecciona un usuario" multiple>
                        <option value=""></option>
                        <?php foreach ($usuarios as $usuario) { ?>
                            <option value="<?php echo $usuario["id_usuario"] ?>" <?php echo isset($datos["id_usuarios_asociados"]) && $usuario["id_usuario"] == $datos["id_usuarios_asociados"] ? "selected" : "" ?>><?php echo $usuario["username"]; ?></option>
                        <?php } ?>
                    </select>

                    <label for="descripcion_proyecto">Descripción del proyecto</label>
                    <textarea id="descripcion_proyecto" name="descripcion_proyecto" placeholder="Introduzca una descripción del proyecto (opcional)" rows="3"><?php echo isset($datos["descripcion_proyecto"]) ? $datos["descripcion_proyecto"] : "" ?></textarea>

                    <input type="submit" value="Enviar" name="enviar" class="botones">
                </form>
        </main>
        <footer>
            <div>
                <p>Proyecto de Fin de Ciclo Superior DAW 2024</p>
            </div>
            <div id="logo-footer">
                <a href="#" class="logo-enlace"><img src="../assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequeñas">
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