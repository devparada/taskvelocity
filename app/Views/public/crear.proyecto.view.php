<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>TaskVelocity | Crear proyecto</title>
        <link rel="stylesheet" href="../../assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="../../assets/css/public/estilosProyectos.css">
    </head>
    <body>
        <main>
            <div class="formlario">
                <form action="<?php echo $seccion; ?>" method="post" enctype="multipart/form-data">         
                    <label for="nombre_proyecto">Nombre del proyecto *</label>
                    <input type="text" id="nombre_proyecto" name="nombre_proyecto" placeholder="Introduzca el nombre del proyecto" value="<?php echo isset($datos["nombre_proyecto"]) ? $datos["nombre_proyecto"] : "" ?>">

                    <label for="imagen_proyecto">Imagen</label>
                    <input type="file" id="imagen_proyecto" accept=".jpg,.png">

                    <label for="fecha_limite_proyecto">Fecha límite</label>
                    <input type="date" id="fecha_limite_proyecto" name="fecha_limite_proyecto" value="<?php echo isset($datos["fecha_limite_proyecto"]) ? $datos["fecha_limite_proyecto"] : "" ?>">

                    <label for="id_usuarios_asociados[]">Usuarios asociados *</label>
                    <select id="id_usuarios_asociados[]" name="id_usuarios_asociados[]" data-placeholder="Selecciona un usuario" multiple>
                        <option value=""></option>
                        <?php foreach ($usuarios as $usuario) { ?>
                            <option value="<?php echo $usuario["id_usuario"] ?>" <?php echo isset($datos["id_usuarios_asociados"]) && $usuario["id_usuario"] == $datos["id_usuarios_asociados"] ? "selected" : "" ?>><?php echo $usuario["username"]; ?></option>
                        <?php } ?>
                    </select>

                    <label for="descripcion_proyecto">Descripción del proyecto</label>
                    <textarea id="descripcion_proyecto" name="descripcion_proyecto" placeholder="Introduzca una descripción del proyecto (opcional)" rows="3"><?php echo isset($datos["descripcion_proyecto"]) ? $datos["descripcion_proyecto"] : "" ?></textarea>

                    <input type="submit" value="Enviar" name="enviar" class="botones">
                </form>
        </main>
    </body>
</html>