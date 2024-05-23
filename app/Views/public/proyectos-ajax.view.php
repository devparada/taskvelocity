<!-- Proyectos que se recargan con AJAX -->
<?php foreach ($proyectos as $p) { ?>
    <?php
    $idProyecto = $p["id_proyecto"];
    if (file_exists("./assets/img/proyectos/proyecto-$idProyecto.jpg")) {
        ?>
        <div class="proyectos" id="<?php echo $p["id_proyecto"] ?>">
            <div class="imagen-contenedor">
                <img src="/assets/img/proyectos/proyecto-<?php echo $p["id_proyecto"] ?>.jpg" alt="Imagen Proyecto <?php echo $p["nombre_proyecto"] ?>" class="imagen-proyecto">        
            <?php } else { ?>
                <div class="proyectos proyectos-sin-imagen" id="<?php echo $p["id_proyecto"] ?>">           
                    <div class="imagen-contenedor">
                    <?php } ?>
                </div>
                <div class="informacion-proyecto">
                    <h3><?php echo $p["nombre_proyecto"] ?></h3>
                    <?php if ($p["editable"] == 1) { ?>
                        <p class="fecha-limite"><?php echo $p["fecha_limite_proyecto"] ?></p>
                        <p>Tareas: <?php echo (!empty($p["tareas"])) ? count($p["tareas"]) : "No tiene" ?></p>
                        <p class="miembros-tarea enlace-imagen-perfil"><?php foreach ($p["nombresUsuarios"] as $nombreUsuario) { ?>
                                <?php foreach ($usuarios as $u) { ?>
                                    <?php if ($u["username"] == $nombreUsuario) { ?>
                                        <a href="/perfil/<?php echo $u["id_usuario"] ?>" class="enlace-imagen-perfil"><img src="/assets/img/usuarios/avatar-<?php echo $u["id_usuario"] ?>.jpg" class="imagen-perfil-pequena"><?php echo $u["username"] ?></a>
                                    <?php } ?>
                                <?php } ?>
                                <?php } ?></p>
                        <p>Propietario: <?php echo isset($p["id_usuario_proyecto_prop"]) && ($p["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $p["username"] ?></p>
                        <div class="botones-proyecto">
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
