<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TaskVelocity | Editando tu perfil</title>
        <!-- Select 2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosPerfil.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasProyectosFormularios.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
    </head>
    <header>
        <div id="logo">
            <a href="/"  class="logo-enlace">
                <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
                <h2>TaskVelocity</h2>
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="/proyectos">Proyectos</a></li>
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
            <a href="/logout" class="botones"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</a>
        </div>
    </header>
    <main>
        <div id="introduccion">
            <h1><?php echo $titulo ?></h1>
        </div>
        <div id="contenido-principal">
            <form action="<?php echo $seccion; ?>" method="post" enctype="multipart/form-data">
                <div id="imagen-editar">
                    <img src="/assets/img/usuarios/avatar-<?php echo $idUsuario ?>" alt="Avatar usuario <?php echo $idUsuario ?>" id="imagen-perfil">
                    <p>Editar avatar <span class="campo-obligatorio">*</span></p>
                    <input type="file" id="imagen_avatar" name="imagen_avatar">
                    <p class="text-danger"><?php echo isset($errores['imagen_avatar']) ? $errores['imagen_avatar'] : ''; ?></p>
                    <div id="informacion-adicional">
                        <p><i class="fa-solid fa-cake-candles"></i><span class="campo-obligatorio">*</span>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo isset($datos["fecha_nacimiento"]) ? $datos["fecha_nacimiento"] : "" ?>">
                            <?php
                            ?></p>
                        <p class="texto-error"><?php echo isset($errores["fecha_nacimiento"]) ? $errores["fecha_nacimiento"] : "" ?></p>
                        <p><i class="fa-solid fa-user-plus"></i> <?php
                            setlocale(LC_TIME, 'es_ES.UTF-8');
                            $fechaUsuario = new DateTimeImmutable($datos["fecha_usuario_creado"]);
                            echo strftime("%e de %B", $fechaUsuario->getTimestamp())
                            ?></p>
                    </div>
                </div>
                <div id="informacion-usuario">
                    <h2 class="apartados-inicio">Hola, <?php echo $datos["username"] ?></h2>
                    <p>Correo electrónico <span class="campo-obligatorio">*</span></p>
                    <input type="email" id="email" name="email" placeholder="Introduce un nuevo email" value="<?php echo isset($datos["email"]) ? $datos["email"] : "" ?>" autocomplete="email">
                    <p class="texto-error"><?php echo isset($errores["email"]) ? $errores["email"] : "" ?></p>

                    <p>Color favorito <span class="campo-obligatorio">*</span></p>
                    <select id="id_color" class="select2" name="id_color" data-placeholder="Selecciona un color">
                        <option value=""></option>
                        <?php foreach ($colores as $color) { ?>
                            <option value="<?php echo $color["id_color"] ?>" 
                                    <?php echo (isset($datos["id_color"]) && $datos["id_color"] == $color["id_color"]) ? "selected" : "" ?>><?php echo $color["simbolo_color"] . " " . $color["nombre_color"]; ?></option>
                                <?php } ?>
                    </select>
                    <p class="texto-error"><?php echo isset($errores["id_color"]) ? $errores["id_color"] : "" ?></p>

                    <div class="campo-formulario">
                        <label for="contrasena">Contraseña <span class="campo-obligatorio">*</span></label>
                        <input type="password" id="contrasena" name="contrasena" placeholder="Introduce una nueva contraseña">
                        <p class="texto-error"><?php echo isset($errores["contrasena"]) ? $errores["contrasena"] : "" ?></p>
                    </div>

                    <div class="campo-formulario">
                        <label for="confirmarContrasena">Confirmar contraseña <span class="campo-obligatorio">*</span></label>
                        <input type="password" id="confirmarContrasena" name="confirmarContrasena" placeholder="Confirma la nueva contraseña">
                        <p class="texto-error"><?php echo isset($errores["confirmarContrasena"]) ? $errores["confirmarContrasena"] : "" ?></p>
                    </div>

                    <p>Tu descripción es:</p> 
                    <textarea name="descripcion_usuario" id="descripcion_usuario" rows="4" placeholder="Introduce una nueva descripción (opcional)"> <?php echo isset($datos["descripcion_usuario"]) ? $datos["descripcion_usuario"] : "" ?></textarea>
                    <p class="texto-error"><?php echo isset($errores["descripcion_usuario"]) ? $errores["descripcion_usuario"] : "" ?></p>
                    <div id="informacion-pie-editar">
                        <a id="boton-editar" href="/perfil/<?php echo $_SESSION["usuario"]["id_usuario"] ?>" class="botones"><i class="fa-solid fa-user-pen"></i> Confirmar</a>
                    </div>
                </div>

                <script src="plugins/jquery/jquery.min.js"></script>
                <!-- Select2 -->
                <script src="plugins/select2/js/select2.full.min.js"></script>
                <script src="assets/js/admin/pages/main.js"></script>
                </main> <!-- Continua en plantillas/footer -->

