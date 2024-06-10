<!-- Tareas que se recargan con AJAX -->
<?php if (empty($tareas) && empty($_SESSION["etiqueta"])) { ?>
    <div class="informacion">
        <p><i class="fa-solid fa-circle-info"></i> Crea tu primera tarea pulsando en el botón Crear una tarea</p>
    </div>
<?php } else if ((empty($tareas)) && !empty($_SESSION["etiqueta"])) { ?>
    <div class="informacion informacion-warning">
        <p><i class="fa-solid fa-circle-info"></i> No se han encontrado tareas con esta etiqueta</p>
    </div>
<?php } ?>
<div id="tareas-grid">

    <?php foreach ($tareas as $proyecto => $tarea) { ?>
        <div class="columnas">
            <h2><?php echo $proyecto ?></h2>
            <?php for ($i = 0; $i < count($tarea); $i++) { ?>
                <div class="tareas" id="<?php echo $tarea[$i]["id_tarea"] ?>" style="background-color: <?php echo $tarea[$i]["valor_color"] ?>">
                    <?php
                    $idTarea = $tarea[$i]["id_tarea"];
                    if (file_exists("./assets/img/tareas/tarea-$idTarea.jpg")) {
                        ?>
                        <img src="/assets/img/tareas/tarea-<?php echo $tarea[$i]["id_tarea"] ?>" alt="Imagen Tarea <?php echo $tarea[$i]["nombre_tarea"] ?>" class="imagen-tarea">        
                    <?php } ?>
                    <div class="informacion-tarea">
                        <p class="titulo-tarjeta tarea-titulo"><?php echo $tarea[$i]["nombre_tarea"] ?></p>
                        <p><span class="color-circulo" style="background-color: <?php echo $tarea[$i]["color_etiqueta"] ?>"></span> <?php echo $tarea[$i]["nombre_etiqueta"] ?></p>
                        <?php if (!empty($tarea[$i]["fecha_limite_tarea"])) { ?>
                            <p class="fecha-limite"><i class="fa-regular fa-clock"></i> <?php echo $tarea[$i]["fecha_limite_tarea"] ?></p>
                        <?php } ?>
                        <p class="miembros-proyecto-tarea"><?php
                            foreach ($tarea[$i]["nombresUsuarios"] as $nombreUsuario) {
                                foreach ($usuarios as $u) {
                                    if ($u["username"] == $nombreUsuario) {
                                        ?>
                                        <a href="/perfil/<?php echo $u["id_usuario"] ?> " class="enlace-imagen-perfil"><img src="/assets/img/usuarios/avatar-<?php echo $u["id_usuario"] ?>" alt="Avatar usuario <?php echo ($_SESSION["usuario"]["username"] == $u["username"]) ? "Tú" : $u["username"] ?>" class='imagen-perfil-pequena'><?php echo ($_SESSION["usuario"]["username"] == $u["username"]) ? "Tú" : $u["username"] ?></a>
                                        <?php
                                    }
                                }
                            }
                            ?></p>
                        <p>Propietario: <?php echo isset($tarea[$i]["id_usuario_tarea_prop"]) && ($tarea[$i]["id_usuario_tarea_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $tarea[$i]["username"] ?></p>
                        <?php if (!empty($tarea[$i]["descripcion_tarea"])) { ?>
                            <p class="descripcion-proyecto-tarea"><?php echo $tarea[$i]["descripcion_tarea"] ?></p>
                        <?php } ?>
                        <div class="botones-proyecto-tarea">
                            <a href="/tareas/editar/<?php echo $tarea[$i]["id_tarea"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                            <?php if ($_SESSION["usuario"]["id_usuario"] == $tarea[$i]["id_usuario_tarea_prop"]) { ?>
                                <a href="/tareas/borrar/<?php echo $tarea[$i]["id_tarea"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php }
    ?>
</div>
