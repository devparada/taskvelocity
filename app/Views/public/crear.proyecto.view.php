<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | <?php echo $titulo ?></title>
        <!-- Select 2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasProyectosCrear.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
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
                        <label for="nombre_proyecto">Nombre del proyecto <span class="campo-obligatorio">*</span></label>
                        <input type="text" id="nombre_proyecto" name="nombre_proyecto" placeholder="Introduzca el nombre del proyecto" size="26" value="<?php echo isset($datos["nombre_proyecto"]) ? $datos["nombre_proyecto"] : "" ?>" required>
                        <p class="texto-error"><?php echo isset($errores["nombre_proyecto"]) ? $errores["nombre_proyecto"] : "" ?></p>
                    </div>

                    <div class="campo-formulario">
                        <label for="imagen_proyecto">Imagen</label>
                        <input type="file" id="imagen_proyecto" name="imagen_proyecto" accept=".jpg,.png">
                        <p class="texto-error"><?php echo isset($errores["imagen_proyecto"]) ? $errores["imagen_proyecto"] : "" ?></p>
                    </div>

                    <div class="campo-formulario">
                        <label for="fecha_limite_proyecto">Fecha límite</label>
                        <input type="date" id="fecha_limite_proyecto" name="fecha_limite_proyecto" value="<?php echo isset($datos["fecha_limite_proyecto"]) ? $datos["fecha_limite_proyecto"] : "" ?>">
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
                    </div>

                    <div class="campo-formulario">
                        <label for="descripcion_proyecto">Descripción del proyecto</label>
                        <textarea id="descripcion_proyecto" name="descripcion_proyecto" placeholder="Introduzca una descripción del proyecto (opcional)" rows="3"><?php echo isset($datos["descripcion_proyecto"]) ? $datos["descripcion_proyecto"] : "" ?></textarea>
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
                <a href="/" class="logo-enlace"><img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
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