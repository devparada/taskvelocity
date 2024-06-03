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
        <link rel="stylesheet" href="assets/css/public/estilosPerfil.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasProyectosFormularios.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
            <a href="/logout" class="botones botones-header"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>
        </div>
    </header>
    <main>
        <div id="introduccion">
            <h1><?php echo $titulo ?></h1>
        </div>
        <div id="contenido-principal">
            <div id="imagen-editar">
                <img src="/assets/img/usuarios/avatar-<?php echo $idUsuario ?>" alt="Avatar usuario <?php echo $datos["username"] ?>" id="imagen-perfil">
                <div id="editar-avatar">
                    <form action="<?php echo $seccion; ?>" method="post" enctype="multipart/form-data">
                        <div class="campo-formulario">
                            <label for="imagen_avatar">Editar avatar</label>
                            <input type="file" id="imagen_avatar" name="imagen_avatar">
                        </div> 
                        <p class="text-danger"><?php echo isset($errores['imagen_avatar']) ? $errores['imagen_avatar'] : ''; ?></p>
                </div>
                <div id="informacion-adicional">
                    <div class="campo-formulario">
                        <div id="fecha-nacimiento">
                            <label for="fecha_nacimiento"><i class="fa-solid fa-cake-candles"></i> Fecha nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo isset($datos["fecha_nacimiento"]) ? $datos["fecha_nacimiento"] : "" ?>">
                                <?php
                                ?>
                        </div>
                    </div>
                    <p class="text-danger"><?php echo isset($errores['fecha_nacimiento']) ? $errores['imagen_avatar'] : ''; ?></p>

                    <p><i class="fa-solid fa-user-plus"></i> <?php
                        setlocale(LC_TIME, 'es_ES.UTF-8');
                        $fechaUsuario = new DateTimeImmutable($datos["fecha_usuario_creado"]);
                        echo strftime("%e de %B", $fechaUsuario->getTimestamp())
                        ?></p>
                </div>
            </div>
            <div id="informacion-usuario-editar">
                <div class="campo-formulario">
                    <h2 class="apartados-inicio-editar">Hola, <?php echo $datos["username"] ?></h2>
                    <label for="username">Nombre de usuario  <span class="campo-obligatorio">*</span></label>
                    <input type="text" id="username" name="username" value="<?php echo isset($datos["username"]) ? $datos["username"] : "" ?>">
                    <p class="texto-error"><?php echo isset($errores["username"]) ? $errores["username"] : "" ?></p>
                </div>

                <div class="campo-formulario">
                    <label for="email">Correo electrónico <span class="campo-obligatorio">*</span></label>
                    <input type="email" id="email" name="email" placeholder="Introduce un nuevo email" value="<?php echo isset($datos["email"]) ? $datos["email"] : "" ?>" autocomplete="email">
                    <p class="texto-error"><?php echo isset($errores["email"]) ? $errores["email"] : "" ?></p>
                </div>

                <div class="campo-formulario">
                    <label for="id_color">Color favorito <span class="campo-obligatorio">*</span></label>
                    <select id="id_color" class="select2" name="id_color" data-placeholder="Selecciona un color">
                        <option value=""></option>
                        <?php foreach ($colores as $color) { ?>
                            <option value="<?php echo $color["id_color"] ?>" 
                                    <?php echo (isset($datos["id_color"]) && $datos["id_color"] == $color["id_color"]) ? "selected" : "" ?>><?php echo $color["simbolo_color"] . " " . $color["nombre_color"]; ?></option>
                                <?php } ?>
                    </select>
                    <p class="texto-error"><?php echo isset($errores["id_color"]) ? $errores["id_color"] : "" ?></p>
                </div>

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

                <div class="campo-formulario">
                    <label for="descripcion_usuario">Tu descripción es:</label> 
                    <textarea name="descripcion_usuario" id="descripcion_usuario" rows="4" placeholder="Introduce una nueva descripción (opcional)"> <?php echo isset($datos["descripcion_usuario"]) ? $datos["descripcion_usuario"] : "" ?></textarea>
                    <p class="texto-error"><?php echo isset($errores["descripcion_usuario"]) ? $errores["descripcion_usuario"] : "" ?></p>
                </div>

                <div class="campo-formulario">
                    <div id="informacion-pie-editar">
                        <input type="submit" id="boton-editar" class="botones confirmar-editar" href="/perfil/<?php echo $_SESSION["usuario"]["id_usuario"] ?>" value="<?php echo $enviar ?>"></input>
                    </div>
                </div>
            </div>
            </form>

            <script src="plugins/jquery/jquery.min.js"></script>
            <!-- Select2 -->
            <script src="plugins/select2/js/select2.full.min.js"></script>
            <script src="assets/js/admin/pages/main.js"></script>
    </main> <!-- Continua en plantillas/footer -->

