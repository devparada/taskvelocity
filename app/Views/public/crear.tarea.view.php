<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | <?php echo $titulo ?></title>
        <!-- Select 2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- Iconos -->
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasProyectosCrear.css">

    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/" class="logo-enlace">
                    <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequeñas">
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
            <h1 class="apartados"><?php echo $titulo ?></h1>
            <div class="formulario">
                <form action="<?php echo $seccion; ?>" method="post" enctype="multipart/form-data">
                    <div class="campo-formulario">
                        <label for="nombre_tarea">Nombre del tarea <span class="campo-obligatorio">*</span></label>
                        <input type="text" id="nombre_tarea" name="nombre_tarea" placeholder="Introduzca el nombre de la tarea" size="26" value="<?php echo isset($datos["nombre_tarea"]) ? $datos["nombre_tarea"] : "" ?>" required>
                        <p class="texto-error"><?php echo isset($errores["nombre_tarea"]) ? $errores["nombre_tarea"] : "" ?></p>
                    </div>

                    <div class="campo-formulario">
                        <label for="imagen_tarea">Imagen</label>
                        <input type="file" id="imagen_tarea" name="imagen_tarea" accept=".jpg,.png">
                        <p class="texto-error"><?php echo isset($errores["imagen_tarea"]) ? $errores["imagen_tarea"] : "" ?></p>
                    </div>

                    <div class="campo-formulario">
                        <label for="fecha_limite_tarea">Fecha límite</label>
                        <input type="date" id="fecha_limite_tarea" name="fecha_limite_tarea" value="<?php echo isset($datos["fecha_limite_tarea"]) ? $datos["fecha_limite_tarea"] : "" ?>">
                        <p class="texto-error"><?php echo isset($errores["fecha_limite_tarea"]) ? $errores["fecha_limite_tarea"] : "" ?></p>
                    </div>
                    <div class="campo-formulario">
                        <label for="id_usuarios_asociados[]">Usuarios asociados</label>
                        <select id="id_usuarios_asociados[]" class="select2" name="id_usuarios_asociados[]" data-placeholder="Selecciona un usuario" size="26" multiple>
                            <option value=""></option>
                            <?php foreach ($usuarios as $usuario) { ?>
                                <option value="<?php echo $usuario["id_usuario"] ?>" 
                                <?php
                                if (isset($datos["nombresUsuarios"])) {
                                    foreach ($datos["nombresUsuarios"] as $nombreUsuario) {
                                        if (trim($nombreUsuario) == $usuario["username"]) {
                                            echo "selected";
                                        }
                                    }
                                }
                                ?>><?php echo $usuario["username"]; ?></option>
                                    <?php } ?>
                        </select>
                        <p class="texto-error"><?php echo isset($errores["id_usuarios_asociados"]) ? $errores["id_usuarios_asociados"] : "" ?></p>
                    </div>

                    <div class="campo-formulario">
                        <label for="id_color_tarea">Color favorito <span class="campo-obligatorio">*</span></label>
                        <select id="id_color_tarea" class="select2" name="id_color_tarea" data-placeholder="Selecciona un color" size="26" required>
                            <option value=""></option>
                            <?php foreach ($colores as $color) { ?>
                                <option value="<?php echo $color["id_color"] ?>" 
                                        <?php echo (!isset($datos["id_color_tarea"]) && $color["id_color"] == 1) ? "selected" : "" ?> <?php echo (isset($datos["id_color_tarea"]) && $datos["id_color_tarea"] == $color["id_color"]) ? "selected" : "" ?> ><?php echo $color["nombre_color"]; ?></option>
                                    <?php } ?>
                        </select>
                        <p class="texto-error"><?php echo isset($errores["id_color_tarea"]) ? $errores["id_color_tarea"] : "" ?></p>
                    </div>

                    <div class="campo-formulario">
                        <label for="id_proyecto_asociado">Proyecto asociado <span class="campo-obligatorio">*</span></label>
                        <select id="id_proyecto_asociado" class="select2" name="id_proyecto_asociado" data-placeholder="Selecciona un proyecto" size="26" required>
                            <option value=""></option>
                            <?php foreach ($proyectos as $proyecto) { ?>
                                <option value="<?php echo $proyecto["id_proyecto"] ?>" 
                                <?php
                                if (!isset($datos["id_proyecto_asociado"]) && $proyecto["editable"] == 0) {
                                    echo "selected";
                                } else if (isset($datos["id_proyecto_asociado"]) && $datos["id_proyecto_asociado"] == $proyecto["id_proyecto"]) {
                                    echo "selected";
                                }
                                ?>><?php echo $proyecto["nombre_proyecto"]; ?></option>
                                    <?php } ?>
                        </select>
                        <p class="texto-error"><?php echo isset($errores["id_proyecto_asociado"]) ? $errores["id_proyecto_asociado"] : "" ?></p>
                    </div>

                    <div class="campo-formulario">
                        <label for="descripcion_tarea">Descripción de la tarea</label>
                        <textarea id="descripcion_tarea" name="descripcion_tarea" placeholder="Introduzca una descripción de la tarea (opcional)" rows="3"><?php echo isset($datos["descripcion_tarea"]) ? $datos["descripcion_tarea"] : "" ?></textarea>
                        <p class="texto-error"><?php echo isset($errores["descripcion_tarea"]) ? $errores["descripcion_tarea"] : "" ?></p>
                    </div>

                    <div class="campo-formulario">
                        <input type="submit" value="Enviar" name="enviar" class="botones">
                    </div>
                </form>
        </main>
        <footer>
            <div>
                <p>Proyecto de Fin de Ciclo Superior DAW 2024</p>
            </div>
            <div id="logo-footer">
                <a href="/" class="logo-enlace"><img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequeñas">
                    <p>TaskVelocity</p>
                </a>
            </div>
            <div id="iconos-footer">
                <a href="https://es.linkedin.com"><i class="fa-brands fa-linkedin"></i></a>
                <a href="https://www.youtube.com"><i class="fa-brands fa-youtube"></i></a>
                <a href="https://twitter.com"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="https://www.instagram.com"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </footer>
        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- Select2 -->
        <script src="plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/js/admin/pages/main.js"></script>
    </body>
</html>