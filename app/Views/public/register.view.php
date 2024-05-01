<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TaskVelocity | Register</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="assets/css/admin/adminlte.min.css">
        <!-- Iconos -->
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="/"><b>TaskVelocity</b></a>
            </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Crear una cuenta</p>
                    <form action="/register" method="post">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Nombre" value="<?php echo isset($datos["username"]) ? $datos["username"] : "" ?>">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <p class="text-danger text-center"><?php echo isset($errores["username"]) ? $errores["username"] : "" ?></p>

                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo isset($datos["email"]) ? $datos["email"] : "" ?>">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <p class="text-danger text-center"><?php echo isset($errores["email"]) ? $errores["email"] : "" ?></p>

                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Contraseña">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <p class="text-danger text-center"><?php echo isset($errores["contrasena"]) ? $errores["contrasena"] : "" ?></p>

                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="confirmarContrasena" id="confirmarContrasena" placeholder="Confirmar contraseña">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <p class="text-danger text-center"><?php echo isset($errores["confirmarContrasena"]) ? $errores["confirmarContrasena"] : "" ?></p>

                        <div class="input-group mb-3">
                            <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo isset($datos["fecha_nacimiento"]) ? $datos["fecha_nacimiento"] : "" ?>">
                        </div>
                        <p class="text-danger text-center"><?php echo isset($errores["fecha_nacimiento"]) ? $errores["fecha_nacimiento"] : "" ?></p>

                        <div class="input-group mb-3">
                            <select class="form-control" name="id_color" id="id_color">
                                <?php foreach ($colores as $c) { ?>
                                    <option value="<?php echo $c["id_color"] ?>" <?php echo (isset($datos["id_color"]) && $c["id_color"] == $datos["id_color"]) ? "selected" : "" ?>><?php echo $c["nombre_color"] ?></option>
                                <?php } ?>
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fa-solid fa-palette"></span>
                                </div>
                            </div>
                        </div>
                        <p class="text-danger text-center"><?php echo isset($errores["id_color"]) ? $errores["id_color"] : "" ?></p>

                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block float-right">Registrar</button>
                            </div>
                        </div>

                        <div class="text-center">
                            <p>¿Ya tienes una cuenta? <a href="/login">Inicia sesión</a></p>
                        </div>
                    </form>
                </div>
            </div>
    </body>
</html>
