<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $titulo ?> | TaskVelocity</title>
        <!-- Estilos propios -->  
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectosVer.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasProyectosVer.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasProyectosFormularios.css">
        <!-- Select 2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->  
        <script src="https://kit.fontawesome.com/e260e3cde1.js" crossorigin="anonymous"></script>
        <!-- Moment -->
        <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/locale/es.js"></script>
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/"  class="logo-enlace">
                    <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
                    <h2>TaskVelocity</h2>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="/proyectos" class="apartado-activo">Proyectos</a></li>
                    <li><a href="/tareas">Tareas</a></li>
                    <li><a href="/contacto">Contacto</a></li>
                </ul>
            </nav>
            <div id="perfil-cerrar">
                <div id="perfil">
                    <a href="/perfil/<?php echo $_SESSION["usuario"]["id_usuario"] ?>" class="enlace-perfil">
                        <img src="/assets/img/usuarios/avatar-<?php echo $_SESSION["usuario"]["id_usuario"] ?>" alt="Avatar usuario <?php echo $_SESSION["usuario"]["username"] ?>">
                        <p><?php echo $_SESSION["usuario"]["username"] ?></p>
                    </a>
                </div>
                <a href="/logout" class="botones"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</a>
            </div>
        </header>
        <main>
            <h1 class="apartados"> <?php echo $proyecto["nombre_proyecto"] ?></h1>
            <div class="proyectos">
                <?php
                $idProyecto = $proyecto["id_proyecto"];
                if (file_exists("./assets/img/proyectos/proyecto-$idProyecto.jpg")) {
                    ?>
                    <img src="/assets/img/proyectos/proyecto-<?php echo $proyecto["id_proyecto"] ?>" class="imagen-proyecto-tarea" alt="Imagen Proyecto <?php echo $proyecto["nombre_proyecto"] ?>">
                <?php } ?>
                <div class="informacion-proyecto">
                    <p id="fecha-limite">Fecha límite: <?php echo isset($proyecto["fecha_limite_proyecto"]) ? $proyecto["fecha_limite_proyecto"] : "No tiene fecha límite" ?></p>
                    <p>Propietario: <?php echo isset($proyecto["id_usuario_proyecto_prop"]) && ($proyecto["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $proyecto["username"] ?></p>
                    <div id="anadirTareaProyecto">
                        <form action="<?php echo $seccion; ?>" method="post" enctype="multipart/form-data">
                            <div class="campo-formulario">
                                <label for="id_tareas_asociadas[]">Añade una tarea al proyecto</label>
                                <select id="id_tareas_asociadas[]" class="select2" name="id_tareas_asociadas[]" data-placeholder="Selecciona una tarea" multiple>
                                    <option value=""></option>
                                    <?php foreach ($todasTareas as $tarea) { ?>
                                        <option value="<?php echo $tarea[0]["id_tarea"] ?>" ><?php echo $tarea[0]["nombre_tarea"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="campo-formulario">
                                <input type="submit" value="Añadir tarea" name="enviar" class="botones">
                            </div>
                        </form>
                    </div>
                    <div id="titulo-tabla">
                        <p>Tareas del proyecto</p>
                    </div>
                    <?php if (!empty($tareas)) { ?>
                        <table id="tabla-tareas">
                            <thead>
                            <th>Nombre</th>
                            <th>Fecha límite</th>
                            <th>Etiqueta</th>
                            </thead>
                            <?php foreach ($tareas as $t) { ?>
                                <tr>
                                    <td><?php echo $t["nombre_tarea"] ?></a></td>
                                    <td><?php echo $t["fecha_limite_tarea"] ?? "No tiene" ?></td>
                                    <td><?php echo $t["nombre_etiqueta"] ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else { ?>
                        <p>No hay tareas asociadas a este proyecto</p>
                    <?php } ?>
                    <p class="enlace-imagen-perfil">Miembros: <?php foreach ($usuarios as $u) { ?>
                            <a href="/perfil/<?php echo $u["id_usuario"] ?> " class="enlace-imagen-perfil"><img src="/assets/img/usuarios/avatar-<?php echo $u["id_usuario"] ?>" class='imagen-perfil-pequena'><?php echo $u["username"] ?></a>
                        <?php }
                        ?></p>
                    <p>Descripción del proyecto: </p>
                    <p><?php echo ($proyecto["descripcion_proyecto"] == "") ? "No tiene descripción" : $proyecto["descripcion_proyecto"] ?></p>
                    <?php if ($proyecto["editable"] == 1) { ?>
                        <div class="botones-proyecto">
                            <a href="/proyectos" class="botones"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                            <a href="/proyectos/borrar/<?php echo $proyecto["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                            <a href="/proyectos/editar/<?php echo $proyecto["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                        </div>
                    <?php } else { ?>
                        <div class="botones-proyecto">
                            <a href="/proyectos" class="botones"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <script src="plugins/jquery/jquery.min.js"></script>
            <!-- Select2 -->
            <script src="plugins/select2/js/select2.full.min.js"></script>
            <script src="assets/js/admin/pages/main.js"></script>

            <script src="assets/js/public/fechasTareasProyectosVer.js"></script>
        </main> <!-- Continua en plantillas/footer -->
