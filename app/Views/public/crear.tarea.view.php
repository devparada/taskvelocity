<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $titulo ?> | TaskVelocity</title>
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/" class="logo-enlace">
                    <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
                    <h1>TaskVelocity</h1>
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
                <a href="/logout" class="botones botones-header"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>
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
                        <?php
                        if (isset($idTarea) && file_exists("./assets/img/tareas/tarea-$idTarea.jpg")) {
                            ?>
                            <img src="/assets/img/tareas/tarea-<?php echo $idTarea ?>" class="imagen-proyecto-tarea" alt="Imagen Tarea <?php echo $idTarea ?>">
                        <?php } ?>
                        <input type="file" id="imagen_tarea" name="imagen_tarea">
                        <?php
                        if (isset($idTarea) && file_exists("./assets/img/tareas/tarea-$idTarea.jpg")) {
                            ?>
                            <?php $_SESSION["historial"] = $_SERVER["REQUEST_URI"] ?>
                            <a href="/eliminar/imagen/<?php echo $idTarea ?>" class="botones">Eliminar imagen</a>
                        <?php } ?>
                        <p class="texto-error"><?php echo isset($errores["imagen_tarea"]) ? $errores["imagen_tarea"] : "" ?></p>
                    </div>
                    <div class="campo-formulario-grupo">
                        <div class="campo-formulario">
                            <label for="fecha_limite_tarea">Fecha límite</label>
                            <input type="date" id="fecha_limite_tarea" name="fecha_limite_tarea" value="<?php echo isset($datos["fecha_limite_tarea"]) ? $datos["fecha_limite_tarea"] : "" ?>">
                        </div>

                        <div class="campo-formulario">
                            <label for="id_usuarios_asociados">Usuarios asociados</label>
                            <select id="id_usuarios_asociados" class="select2" name="id_usuarios_asociados[]" multiple>
                                <option></option>
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
                                    if (!isset($datos["id_proyecto"]) && $proyecto["id_proyecto"] == $_SESSION["usuario"]["id_proyecto_personal"] || (isset($_GET["proyecto"]) && $proyecto["id_proyecto"] == $_GET["proyecto"])) {
                                        echo "selected";
                                    } else if (isset($datos["id_proyecto"]) && $datos["id_proyecto"] == $proyecto["id_proyecto"]) {
                                        echo "selected";
                                    }
                                    ?>><?php echo $proyecto["nombre_proyecto"] ?></option>
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

                    <?php if (isset($modoEdit)) { ?>
                        <input type="hidden" id="usuarios_selecionados" value='<?php echo html_entity_decode($usuarios) ?>'>
                    <?php } ?>

                    <div class="campo-formulario">
                        <input type="submit" value="<?php echo $enviar ?>" name="enviar" class="botones">
                    </div>
                </form>
                <script src="plugins/jquery/jquery.min.js"></script>
                <!-- Select2 -->
                <script src="plugins/select2/js/select2.full.min.js"></script>
                <script src="plugins/select2/js/i18n/es.js"></script>
                <script src="assets/js/admin/pages/main.js"></script>

                <script src="assets/js/public/mostrarUsuariosAsync.js"></script>

                <script>
                    $(document).ready(function () {
                        $("#id_proyecto_asociado").select2({
                            theme: 'bootstrap4',
                            allowClear: true,
                            closeOnSelect: true
                        });
                    });
                </script>
        </main> <!-- Continua en plantillas/footer -->
