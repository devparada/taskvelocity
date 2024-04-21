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
                        <div class="mb-3 col-sm-3">
                            <label for="nombre_tarea">Nombre de la tarea *</label>
                            <input type="text" class="form-control" id="nombre_tarea" name="nombre_tarea" placeholder="Introduzca el nombre de la tarea" value="<?php echo isset($datos["nombre_tarea"]) ? $datos["nombre_tarea"] : "" ?>" <?php echo isset($modoVer) || isset($modoEdit) ? "readonly" : "" ?> >
                            <p class="text-danger"><?php echo isset($errores['nombre_tarea']) ? $errores['nombre_tarea'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="imagen_tarea">Imagen</label>
                            <input type="file" class="form-control-file" id="imagen_tarea">
                            <p class="text-danger"><?php echo isset($errores['imagen_tarea']) ? $errores['imagen_tarea'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="fecha_limite_tarea">Fecha límite</label>
                            <input type="date" class="form-control" id="fecha_limite_tarea" name="fecha_limite_tarea" value="<?php echo isset($datos["fecha_nacimiento"]) ? $datos["fecha_nacimiento"] : "" ?>" <?php echo isset($modoVer) ? "readonly" : "" ?>>
                            <p class="text-danger"><?php echo isset($errores['fecha_limite_tarea']) ? $errores['fecha_limite_tarea'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="id_color_tarea">Color de la tarea *</label>
                            <select class="form-control" id="id_color_tarea" name="id_color_tarea" <?php echo isset($modoVer) ? "disabled" : "" ?>>
                                <?php foreach ($colores as $color) { ?>
                                    <option value="<?php echo $color["id_color"] ?>" <?php echo isset($datos["id_color_tarea"]) && $color["id_color"] == $datos["id_color_tarea"] ? "selected" : "" ?>><?php echo $color["nombre_color"]; ?></option>
                                <?php } ?>
                            </select>
                            <p class="text-danger"><?php echo isset($errores['id_color_tarea']) ? $errores['id_color_tarea'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="id_proyecto_asociado">Proyecto asociado *</label>
                            <select class="form-control" id="id_proyecto_asociado" name="id_proyecto_asociado" <?php echo isset($modoVer) ? "disabled" : "" ?>>
                                <option value="">Selecciona un proyecto</option>
                                <?php foreach ($proyectos as $proyecto) { ?>
                                    <option value="<?php echo $proyecto["id_proyecto"] ?>" <?php echo isset($datos["id_proyecto_asociado"]) && $proyecto["id_proyecto"] == $datos["id_proyecto_asociado"] ? "selected" : "" ?>><?php echo $proyecto["nombre_proyecto"]; ?></option>
                                <?php } ?>
                            </select>
                            <p class="text-danger"><?php echo isset($errores['id_proyecto_asociado']) ? $errores['id_proyecto_asociado'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-3">
                            <label for="id_usuarios_asociados[]">Usuarios asociados *</label>
                            <select class="form-control" id="id_usuarios_asociados[]" name="id_usuarios_asociados[]" multiple <?php echo isset($modoVer) ? "disabled" : "" ?>>
                                <option value=""></option>
                                <?php foreach ($usuarios as $usuario) { ?>
                                    <option value="<?php echo $usuario["id_usuario"] ?>" <?php echo isset($datos["id_usuarios_asociados"]) && $usuario["id_usuario"] == $datos["id_usuarios_asociados"] ? "selected" : "" ?>><?php echo $usuario["username"]; ?></option>
                                <?php } ?>
                            </select>
                            <p class="text-danger"><?php echo isset($errores['id_usuarios_asociados']) ? $errores['id_usuarios_asociados'] : ''; ?></p>
                        </div>

                        <div class="mb-3 col-sm-6">
                            <label for="descripcion_tarea">Descripción de la tarea</label>
                            <textarea class="form-control" id="descripcion_tarea" name="descripcion_tarea" placeholder="Introduzca una descripción de la tarea (opcional)" rows="3" <?php echo isset($modoVer) ? "readonly" : "" ?>><?php echo isset($datos["descripcion_tarea"]) ? $datos["descripcion_tarea"] : "" ?></textarea>
                            <p class="text-danger"><?php echo isset($errores['descripcion_tarea']) ? $errores['descripcion_tarea'] : ''; ?></p>
                        </div>

                        <div class="col-12 text-right">
                            <?php
                            if (!isset($modoVer)) {
                                ?>
                                <input type="submit" value="Enviar" name="enviar" class="btn btn-primary"/>
                                <a href="/tareas" class="btn btn-danger ml-3">Cancelar</a>
                            <?php } else { ?>
                                <a href="/tareas" class="btn btn-danger ml-3">Volver</a>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>                        
</div>