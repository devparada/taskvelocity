<!-- Tareas que se recargan con AJAX -->
<?php foreach ($tareas as $proyecto => $value) { ?>
    <div class="columnas">
        <h2><?php echo $proyecto ?></h2>
        <?php for ($i = 0; $i < count($value); $i++) { ?>
            <div class="tarjetas" id="<?php echo $value[$i]["id_tarea"] ?>" style="background-color: <?php echo $value[$i]["valor_color"] ?>">
                <?php
                $idTarea = $value[$i]["id_tarea"];
                if (file_exists("./assets/img/tareas/tarea-$idTarea.jpg")) {
                    ?>
                    <img src="/assets/img/tareas/tarea-<?php echo $value[$i]["id_tarea"] ?>" alt="Imagen Tarea <?php echo $value[$i]["nombre_tarea"] ?>" class="imagen-proyecto">        
                <?php } ?>
                <div class="informacion-tarea">
                    <p class="titulo-tarjeta tarea-titulo"><?php echo $value[$i]["nombre_tarea"] ?></p>
                    <p><span class="color-circulo" style="background-color: <?php echo $value[$i]["color_etiqueta"] ?>"></span> <?php echo $value[$i]["nombre_etiqueta"] ?></p>
                    <p class="fecha-limite"><?php echo $value[$i]["fecha_limite_tarea"] ?></p>
                    <p class="miembros-tarea"><?php
                        foreach ($value[$i]["nombresUsuarios"] as $nombreUsuario) {
                            foreach ($usuarios as $u) {
                                if ($u["username"] == $nombreUsuario) {
                                    ?>
                                    <a href="/perfil/<?php echo $u["id_usuario"] ?> " class="enlace-imagen-perfil"><img src="/assets/img/usuarios/avatar-<?php echo $u["id_usuario"] ?>" alt="Avatar usuario <?php echo $u["username"] ?>" class='imagen-perfil-pequena'><?php echo $nombreUsuario ?></a>
                                    <?php
                                }
                            }
                        }
                        ?></p>
                    <p>Propietario: <?php echo isset($value[$i]["id_usuario_tarea_prop"]) && ($value[$i]["id_usuario_tarea_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $value[$i]["username"] ?></p>
                    <p class="descripcion-tarea"><?php echo ($value[$i]["descripcion_tarea"] == "") ? "No tiene descripción" : $value[$i]["descripcion_tarea"] ?></p>
                    <div class="botones-tareas">
                        <a href="/tareas/editar/<?php echo $value[$i]["id_tarea"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                        <a href="/tareas/borrar/<?php echo $value[$i]["id_tarea"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php
}
