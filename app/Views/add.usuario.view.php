<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $tituloDiv; ?></h6>                                    
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <form action="<?php echo $seccion; ?>" method="post" enctype="multipart/form-data">         
                    <div class="row">
                        <div class="mb-3 col-sm-4">
                            <label for="username">Nombre de usuario *</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Introduzca un nombre de usuario" autocomplete="username" value="<?php echo isset($datos["username"]) ? $datos["username"] : "" ?>" <?php echo isset($modoVer) || isset($modoEdit) ? "readonly" : "" ?> >
                            <p class="text-danger"><?php echo isset($errores['username']) ? $errores['username'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-5">
                            <label for="avatar">Avatar</label>
                            <input type="file" class="form-control-file" id="avatar" name="avatar" <?php echo isset($modoVer) ? "disabled" : "" ?>>
                            <p class="text-danger"><?php echo isset($errores['avatar']) ? $errores['avatar'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="id_rol">Rol *</label>
                            <select class="form-control" id="id_rol" name="id_rol" <?php echo isset($modoVer) ? "disabled" : "" ?>>
                                <option value="">Selecciona un rol</option>
                                <?php foreach ($roles as $rol) { ?>
                                    <option value="<?php echo $rol["id_rol"] ?>" <?php echo isset($datos["id_rol"]) && $rol["id_rol"] == $datos["id_rol"] ? "selected" : "" ?>><?php echo $rol["nombre_rol"]; ?></option>
                                <?php } ?>
                            </select>
                            <p class="text-danger"><?php echo isset($errores['id_rol']) ? $errores['id_rol'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-4">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Introduzca un email" autocomplete="email" value="<?php echo isset($datos["email"]) ? $datos["email"] : "" ?>" <?php echo isset($modoVer) ? "readonly" : "" ?>>
                            <p class="text-danger"><?php echo isset($errores['email']) ? $errores['email'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-4">
                            <label for="contrasena">Contraseña *</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" <?php echo isset($modoVer) ? "readonly" : "" ?>>
                            <p class="text-danger"><?php echo isset($errores['contrasena']) ? $errores['contrasena'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-4">
                            <label for="confirmarContrasena">Confirmar contraseña *</label>
                            <input type="password" class="form-control" id="confirmarContrasena" name="confirmarContrasena" <?php echo isset($modoVer) ? "readonly" : "" ?>>
                            <p class="text-danger"><?php echo isset($errores['confirmarContrasena']) ? $errores['confirmarContrasena'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="fecha_nacimiento">Fecha de nacimiento *</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo isset($datos["fecha_nacimiento"]) ? $datos["fecha_nacimiento"] : "" ?>" <?php echo isset($modoVer) ? "readonly" : "" ?>>
                            <p class="text-danger"><?php echo isset($errores['fecha_nacimiento']) ? $errores['fecha_nacimiento'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="id_color">Color favorito</label>
                            <select class="form-control" id="id_color" name="id_color"  <?php echo isset($modoVer) ? "disabled" : "" ?>>
                                <option value="">Selecciona un color</option>
                                <?php foreach ($colores as $color) { ?>
                                    <option value="<?php echo $color["id_color"] ?>" <?php echo isset($datos["id_color"]) && $color["id_color"] == $datos["id_color"] ? "selected" : "" ?>><?php echo $color["nombre_color"]; ?></option>
                                <?php } ?>
                            </select>
                            <p class="text-danger"><?php echo isset($errores['id_color']) ? $errores['id_color'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-6">
                            <label for="descripcion_usuario">Descripción del usuario</label>
                            <textarea class="form-control" id="descripcion_usuario" name="descripcion_usuario" placeholder="Introduzca una descripción del usuario (opcional)" rows="3" <?php echo isset($modoVer) ? "readonly" : "" ?>><?php echo isset($datos["descripcion_usuario"]) ? $datos["descripcion_usuario"] : "" ?></textarea>
                            <p class="text-danger"><?php echo isset($errores['descripcion_usuario']) ? $errores['descripcion_usuario'] : ''; ?></p>
                        </div>

                        <div class="col-12 text-right">
                            <?php
                            if (!isset($modoVer)) {
                                ?>
                                <input type="submit" value="Enviar" name="enviar" class="btn btn-primary"/>
                                <a href="/usuarios" class="btn btn-danger ml-3">Cancelar</a>
                            <?php } else { ?>
                                <a href="/usuarios" class="btn btn-danger ml-3">Volver</a>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>                        
</div>