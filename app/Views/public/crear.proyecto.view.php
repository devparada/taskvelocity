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
                    <h2>TaskVelocity</h2>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="/proyectos" class="apartado-activo">Proyectos</a></li>
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
                <a href="/logout" class="botones botones-header"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>
            </div>
        </header>
        <main>
            <h1 class="apartados"><?php echo $titulo ?></h1>
            <div class="formulario">
                <form action="<?php echo $seccion; ?>" method="post" enctype="multipart/form-data">
                    <div class="campo-formulario">
                        <label for="nombre_proyecto">Nombre del proyecto <span class="campo-obligatorio">*</span></label>
                        <input type="text" id="nombre_proyecto" name="nombre_proyecto" placeholder="Introduzca el nombre del proyecto" size="26" value="<?php echo isset($datos["nombre_proyecto"]) ? $datos["nombre_proyecto"] : "" ?>">
                    </div>
                    <p class="texto-error"><?php echo isset($errores["nombre_proyecto"]) ? $errores["nombre_proyecto"] : "" ?></p>

                    <div class="campo-formulario">
                        <label for="imagen_proyecto">Imagen</label>
                        <?php
                        if (file_exists("./assets/img/proyectos/proyecto-$idProyecto.jpg")) {
                            ?>
                            <img src="/assets/img/proyectos/proyecto-<?php echo $idProyecto ?>" class="imagen-proyecto-tarea" alt="Imagen Proyecto <?php echo $idProyecto ?>">
                        <?php } ?>
                        <input type="file" id="imagen_proyecto" name="imagen_proyecto" accept=".jpg,.png">
                    </div>
                    <p class="texto-error"><?php echo isset($errores["imagen_proyecto"]) ? $errores["imagen_proyecto"] : "" ?></p>

                    <div class="campo-formulario-grupo">
                        <div class="campo-formulario">
                            <label for="fecha_limite_proyecto">Fecha límite</label>
                            <input type="date" id="fecha_limite_proyecto" name="fecha_limite_proyecto" value="<?php echo isset($datos["fecha_limite_proyecto"]) ? $datos["fecha_limite_proyecto"] : "" ?>">
                        </div>

                        <div class="campo-formulario">
                            <label for="id_usuarios_asociados[]">Usuarios asociados</label>
                            <select id="id_usuarios_asociados" class="select2" name="id_usuarios_asociados[]" data-placeholder="Selecciona un usuario" size="26" multiple>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <p class="texto-error"><?php echo isset($errores["fecha_limite_proyecto"]) ? $errores["fecha_limite_proyecto"] : "" ?></p>
                    <p class="texto-error"><?php echo isset($errores["id_usuarios_asociados"]) ? $errores["id_usuarios_asociados"] : "" ?></p>

                    <?php if (isset($modoEdit)) { ?>
                        <input type="hidden" id="usuarios_selecionados" value='<?php echo html_entity_decode($usuarios) ?>'>
                    <?php } ?>

                    <div class="campo-formulario">
                        <label for="descripcion_proyecto">Descripción del proyecto</label>
                        <textarea id="descripcion_proyecto" name="descripcion_proyecto" placeholder="Introduzca una descripción del proyecto (opcional)" rows="3"><?php echo isset($datos["descripcion_proyecto"]) ? $datos["descripcion_proyecto"] : "" ?></textarea>
                    </div>
                    <p class="texto-error"><?php echo isset($errores["descripcion_proyecto"]) ? $errores["descripcion_proyecto"] : "" ?></p>

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
        </main> <!-- Continua en plantillas/footer -->
