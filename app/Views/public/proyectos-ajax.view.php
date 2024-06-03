<!-- Proyectos que se recargan con AJAX -->
<?php foreach ($proyectos as $p) { ?>
    <?php
    $idProyecto = $p["id_proyecto"];
    if (file_exists("./assets/img/proyectos/proyecto-$idProyecto.jpg")) {
        ?>
        <div class="proyectos proyectos-con-imagen" id="<?php echo $p["id_proyecto"] ?>" style="background-image: url(./assets/img/proyectos/proyecto-<?php echo $p["id_proyecto"] ?>.jpg)">
        <?php } else { ?>
            <div class="proyectos" id="<?php echo $p["id_proyecto"] ?>">           
            <?php } ?>
            <div class="informacion-proyecto">
                <p class="titulo-tarjeta"><?php echo $p["nombre_proyecto"] ?></p>
                <?php if ($p["editable"] == 1) { ?>
                    <?php if (!empty($p["fecha_limite_proyecto"])) { ?>
                        <p class="fecha-limite"><?php echo $p["fecha_limite_proyecto"] ?></p>
                    <?php } ?>
                    <p>Tareas: <?php echo (!empty($p["tareas"])) ? count($p["tareas"]) : "No tiene" ?></p>
                    <p class="miembros-proyecto-tarea enlace-imagen-perfil"><?php foreach ($p["nombresUsuarios"] as $nombreUsuario) { ?>
                            <?php foreach ($usuarios as $u) { ?>
                                <?php if ($u["username"] == $nombreUsuario) { ?>
                                    <a href="/perfil/<?php echo $u["id_usuario"] ?>" class="enlace-imagen-perfil"><img src="/assets/img/usuarios/avatar-<?php echo $u["id_usuario"] ?>.jpg" alt="Avatar <?php echo ($u["username"] == $_SESSION["usuario"]["username"]) ? "Tú" : $u["username"] ?>" class="imagen-perfil-pequena"><?php echo ($u["username"] == $_SESSION["usuario"]["username"]) ? "Tú" : $u["username"] ?></a>
                                <?php } ?>
                            <?php } ?>
                            <?php } ?></p>
                    <p>Propietario: <?php echo isset($p["id_usuario_proyecto_prop"]) && ($p["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $p["username"] ?></p>
                    <?php if(!empty($p["descripcion_proyecto"])) { ?>
                    <p class="descripcion-proyecto-tarea"><?php echo $p["descripcion_proyecto"] ?></p>
                    <?php } ?>
                    <div class="botones-proyecto-tarea">
                        <a href="/proyectos/editar/<?php echo $p["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                        <a href="/proyectos/borrar/<?php echo $p["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                    </div>
                <?php } else { ?>
                    <p id="texto-personal">Este es tu proyecto personal se usa por defecto y no se puede borrar</p>
                <?php } ?>
            </div>
        </div>
        <?php
    }
