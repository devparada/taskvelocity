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
                <form action="<?php echo $seccion; ?>" method="post">         
                    <div class="row">
                        <div class="mb-3 col-sm-4">
                            <label for="username">Nombre de usuario *</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Introduzca un username" autocomplete="username" value="<?php echo isset($datos["username"]) ? $datos["username"] : "" ?>" >
                            <p class="text-danger"><?php echo isset($errores['username']) ? $errores['username'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-5">
                            <label for="avatar">Avatar</label>
                            <input type="file" class="form-control-file" id="avatar" name="avatar" value="">
                            <p class="text-danger"><?php echo isset($errores['avatar']) ? $errores['avatar'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="idRol">Rol *</label>
                            <select class="form-control" id="idRol" name="idRol">
                                <option value=""></option>
                                <?php foreach ($roles as $rol) { ?>
                                    <option value="<?php echo $rol["id_rol"] ?>" <?php echo isset($datos["idRol"]) && $rol["id_rol"] == $datos["idRol"] ? "selected" : "" ?>><?php echo $rol["nombre_rol"]; ?></option>
                                <?php } ?>
                            </select>
                            <p class="text-danger"><?php echo isset($errores['idRol']) ? $errores['idRol'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-4">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Introduzca un email" autocomplete="email" value="<?php echo isset($datos["email"]) ? $datos["email"] : "" ?>" >
                            <p class="text-danger"><?php echo isset($errores['email']) ? $errores['email'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-4">
                            <label for="contrasena">Contraseña *</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" >
                            <p class="text-danger"><?php echo isset($errores['contrasena']) ? $errores['contrasena'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-4">
                            <label for="confirmarContrasena">Confirmar contraseña *</label>
                            <input type="password" class="form-control" id="confirmarContrasena" name="confirmarContrasena" >
                            <p class="text-danger"><?php echo isset($errores['confirmarContrasena']) ? $errores['confirmarContrasena'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="fechaNac">Fecha de nacimiento *</label>
                            <input type="date" class="form-control" id="fechaNac" name="fechaNac" value="<?php echo isset($datos["fechaNac"]) ? $datos["fechaNac"] : "" ?>" >
                            <p class="text-danger"><?php echo isset($errores['fechaNac']) ? $errores['fechaNac'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="idColorFav">Color favorito</label>
                            <select class="form-control" id="idColorFav" name="idColorFav">
                                <option value=""></option>
                                <?php foreach ($colores as $color) { ?>
                                    <option value="<?php echo $color["id_color"] ?>" <?php echo isset($datos["idColorFav"]) && $color["id_color"] == $datos["idColorFav"] ? "selected" : "" ?>><?php echo $color["nombre_color"]; ?></option>
                                <?php } ?>
                            </select>
                            <p class="text-danger"><?php echo isset($errores['idColorFav']) ? $errores['idColorFav'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-6">
                            <label for="descripcion">Descripción del usuario</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Introduzca una descripción del usuario (opcional)" rows="3"><?php echo isset($datos["descripcion"]) ? $datos["descripcion"] : "" ?></textarea>
                            <p class="text-danger"><?php echo isset($errores['descripcion']) ? $errores['descripcion'] : ''; ?></p>
                        </div>

                        <div class="col-12 text-right">                            
                            <input type="submit" value="Enviar" name="enviar" class="btn btn-primary"/>
                            <a href="/usuarios" class="btn btn-danger ml-3">Cancelar</a>                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>                        
</div>