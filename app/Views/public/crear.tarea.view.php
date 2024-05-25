<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $titulo ?> | TaskVelocity</title>
        <!-- Select 2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasProyectosFormularios.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <script src="https://kit.fontawesome.com/e260e3cde1.js" crossorigin="anonymous"></script>

    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/" class="logo-enlace">
                    <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
                    <h2>TaskVelocity</h2>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="/proyectos">Proyectos</a></li>
                    <li><a href="/tareas" class="apartado-activo">Tareas</a></li>
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
            <h1 class="apartados"><?php echo $titulo ?></h1>
            <div class="formulario">
                <form action="<?php echo $seccion; ?>" method="post" enctype="multipart/form-data">
                    <div class="campo-formulario-grupo">
                        <div class="campo-formulario">
                            <label for="nombre_tarea">Nombre del tarea <span class="campo-obligatorio">*</span></label>
                            <input type="text" id="nombre_tarea" name="nombre_tarea" placeholder="Introduzca el nombre de la tarea" size="26" value="<?php echo isset($datos["nombre_tarea"]) ? $datos["nombre_tarea"] : "" ?>">
                        </div>

                        <div class="campo-formulario">
                            <label for="id_etiqueta">Etiqueta  <span class="campo-obligatorio">*</span></label>
                            <select id="id_etiqueta" class="select2" name="id_etiqueta" data-placeholder="Selecciona una etiqueta">
                                <option value=""></option>
                                <?php foreach ($etiquetas as $etiqueta) { ?>
                                    <option value="<?php echo $etiqueta["id_etiqueta"] ?>" 
                                            <?php echo (!isset($datos["id_etiqueta"])) && $etiqueta["id_etiqueta"] == 1 ? "selected" : "" ?> <?php echo (isset($datos["id_etiqueta"]) && $datos["id_etiqueta"] == $etiqueta["id_etiqueta"]) ? "selected" : "" ?> ><?php echo $etiqueta["nombre_etiqueta"]; ?></option>
                                        <?php } ?>
                            </select>
                        </div>
                    </div>
                    <p class="texto-error"><?php echo isset($errores["nombre_tarea"]) ? $errores["nombre_tarea"] : "" ?></p>
                    <p class="texto-error"><?php echo isset($errores["id_etiqueta"]) ? $errores["id_etiqueta"] : "" ?></p>


                    <div class="campo-formulario">
                        <label for="imagen_tarea">Imagen</label>
                        <input type="file" id="imagen_tarea" name="imagen_tarea" accept=".jpg,.png">
                        <p class="texto-error"><?php echo isset($errores["imagen_tarea"]) ? $errores["imagen_tarea"] : "" ?></p>
                    </div>
                    <div class="campo-formulario-grupo">
                        <div class="campo-formulario">
                            <label for="fecha_limite_tarea">Fecha límite</label>
                            <input type="date" id="fecha_limite_tarea" name="fecha_limite_tarea" value="<?php echo isset($datos["fecha_limite_tarea"]) ? $datos["fecha_limite_tarea"] : "" ?>">
                        </div>

                        <div class="campo-formulario">
                            <label for="id_usuarios_asociados[]">Usuarios asociados</label>
                            <select id="id_usuarios_asociados[]" class="select2" name="id_usuarios_asociados[]" data-placeholder="Escoja un usuario" size="26" multiple>
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
                        </div>                                 
                    </div>
                    <p class="texto-error"><?php echo isset($errores["fecha_limite_tarea"]) ? $errores["fecha_limite_tarea"] : "" ?></p>
                    <p class="texto-error"><?php echo isset($errores["id_usuarios_asociados"]) ? $errores["id_usuarios_asociados"] : "" ?></p>

                    <div class="campo-formulario-grupo">
                        <div class="campo-formulario">
                            <label for="id_color_tarea">Color favorito <span class="campo-obligatorio">*</span></label>
                            <select id="id_color_tarea" class="select2" name="id_color_tarea" data-placeholder="Selecciona un color" size="26">
                                <option value=""></option>
                                <?php foreach ($colores as $color) { ?>
                                    <option value="<?php echo $color["id_color"] ?>" 
                                            <?php echo (!isset($datos["id_color_tarea"]) && $color["id_color"] == $_SESSION["usuario"]["id_color_favorito"]) ? "selected" : "" ?> <?php echo (isset($datos["id_color_tarea"]) && $datos["id_color_tarea"] == $color["id_color"]) ? "selected" : "" ?>><?php echo $color["simbolo_color"] . " " . $color["nombre_color"]; ?></option>
                                        <?php } ?>
                            </select>
                        </div>
                        
                        <div class="campo-formulario">
                            <label for="id_proyecto_asociado">Proyecto asociado <span class="campo-obligatorio">*</span></label>
                            <select id="id_proyecto_asociado" class="select2" name="id_proyecto_asociado" data-placeholder="Selecciona un proyecto" size="26">
                                <option value=""></option>
                                <?php foreach ($proyectos as $proyecto) { ?>
                                    <option value="<?php echo $proyecto["id_proyecto"] ?>" 
                                    <?php
                                    if (!isset($datos["id_proyecto"]) && $proyecto["id_proyecto"] == $usuario["id_proyecto_personal"]) {
                                        echo "selected";
                                    } else if (isset($datos["id_proyecto"]) && $datos["id_proyecto"] == $proyecto["id_proyecto"]) {
                                        echo "selected";
                                    }
                                    ?>><?php echo $proyecto["nombre_proyecto"]; ?></option>
                                        <?php } ?>
                            </select>
                        </div>
                    </div>
                    <p class="texto-error"><?php echo isset($errores["id_color_tarea"]) ? $errores["id_color_tarea"] : "" ?></p>
                    <p class="texto-error"><?php echo isset($errores["id_proyecto_asociado"]) ? $errores["id_proyecto_asociado"] : "" ?></p>

                    <div class="campo-formulario">
                        <label for="descripcion_tarea">Descripción de la tarea</label>
                        <textarea id="descripcion_tarea" name="descripcion_tarea" placeholder="Introduzca una descripción de la tarea (opcional)" rows="3"><?php echo isset($datos["descripcion_tarea"]) ? $datos["descripcion_tarea"] : "" ?></textarea>
                        <p class="texto-error"><?php echo isset($errores["descripcion_tarea"]) ? $errores["descripcion_tarea"] : "" ?></p>
                    </div>

                    <div class="campo-formulario">
                        <input type="submit" value="Enviar" name="enviar" class="botones">
                    </div>
                </form>
                <script src="plugins/jquery/jquery.min.js"></script>
                <!-- Select2 -->
                <script src="plugins/select2/js/select2.full.min.js"></script>
                <script src="assets/js/admin/pages/main.js"></script>
        </main> <!-- Continua en plantillas/footer -->
