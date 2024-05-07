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
                        <div class="mb-3 col-sm-5">
                            <label for="nombre_proyecto">Nombre del proyecto <span class="campo-obligatorio">*</span></label>
                            <input type="text" class="form-control" id="nombre_proyecto" name="nombre_proyecto" placeholder="Introduzca el nombre del proyecto" required value="<?php echo isset($datos["nombre_proyecto"]) ? $datos["nombre_proyecto"] : "" ?>" <?php echo isset($modoVer) || isset($modoEdit) ? "readonly" : "" ?> >
                            <p class="text-danger"><?php echo isset($errores['nombre_proyecto']) ? $errores['nombre_proyecto'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-4">
                            <label for="imagen_proyecto">Imagen</label>
                            <?php if (!isset($modoVer)) { ?>
                                <input type="file" class="form-control-file" id="imagen_proyecto" accept=".jpg,.png" <?php echo isset($modoVer) ? "disabled" : "" ?>>
                            <?php } else { ?>
                                <?php
                                (file_exists("assets/img/proyectos/proyecto-$idProyecto.png")) ? $extension = "png" : $extension = "jpg";
                                if (file_exists("assets/img/proyectos/proyecto-$idProyecto.$extension")) {
                                    ?>
                                    <img src="assets/img/proyectos/proyecto-<?php echo $idProyecto . "." . $extension ?>" class="imagen-mostrar" id="imagen_proyecto">
                                <?php } else { ?>
                                    <p>Este proyecto no tiene imagen</p>
                                <?php } ?>
                            <?php } ?>
                            <p class="text-danger"><?php echo isset($errores['imagen_proyecto']) ? $errores['imagen_proyecto'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="fecha_limite_proyecto">Fecha límite</label>
                            <input type="date" class="form-control" id="fecha_limite_proyecto" name="fecha_limite_proyecto" value="<?php echo isset($datos["fecha_limite_proyecto"]) ? $datos["fecha_limite_proyecto"] : "" ?>" <?php echo isset($modoVer) ? "readonly" : "" ?>>
                            <p class="text-danger"><?php echo isset($errores['fecha_limite_proyecto']) ? $errores['fecha_limite_proyecto'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-5">
                            <label for="id_usuarios_asociados[]">Usuarios asociados</label>
                            <select class="form-control select2" id="id_usuarios_asociados[]" name="id_usuarios_asociados[]" data-placeholder="Selecciona un usuario" multiple <?php echo isset($modoVer) ? "disabled" : "" ?>>
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
                            <p class="text-danger"><?php echo isset($errores['id_usuarios_asociados']) ? $errores['id_usuarios_asociados'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-7">
                            <label for="descripcion_proyecto">Descripción del proyecto</label>
                            <textarea class="form-control" id="descripcion_proyecto" name="descripcion_proyecto" placeholder="Introduzca una descripción del proyecto (opcional)" rows="3" <?php echo isset($modoVer) ? "readonly" : "" ?>><?php echo isset($datos["descripcion_proyecto"]) ? $datos["descripcion_proyecto"] : "" ?></textarea>
                            <p class="text-danger"><?php echo isset($errores['descripcion_proyecto']) ? $errores['descripcion_proyecto'] : ''; ?></p>
                        </div>

                        <div class="col-12 text-right">
                            <?php
                            if (!isset($modoVer)) {
                                ?>
                                <input type="submit" value="Enviar" name="enviar" class="btn btn-primary"/>
                                <a href="/admin/proyectos" class="btn btn-danger ml-3">Cancelar</a>
                            <?php } else { ?>
                                <a href="/admin/proyectos" class="btn btn-danger ml-3">Volver</a>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>                        
</div>