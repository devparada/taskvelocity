<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $titulo ?> | TaskVelocity</title>
        <!-- BootStrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <!-- Estilos propios -->  
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
        <link rel="stylesheet"href="assets/css/public/estilosProyectosTareas.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectosVer.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareasProyectosFormularios.css">
        <!-- Select 2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!-- Moment -->
        <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/locale/es.js"></script>
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/"  class="logo-enlace">
                    <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
                    <h1>TaskVelocity</h1>
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
                <a href="/logout" class="botones botones-header"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>
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
                <div class="informacion-proyecto informacion-proyecto-ver">
                    <?php if (!empty($proyecto["fecha_limite_proyecto"])) { ?>
                        <p id="fecha-limite">Fecha límite: <?php echo ($proyecto["fecha_limite_proyecto"]) ?></p>
                    <?php } ?>
                    <p>Propietario: <?php echo isset($proyecto["id_usuario_proyecto_prop"]) && ($proyecto["id_usuario_proyecto_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $proyecto["username"] ?></p>
                    <div id="anadirTareaProyecto">
                        <div class="campo-formulario">
                            <div id="titulo-anadir">
                                <label for="id_tareas_asociadas">Añade o crea una tarea en el proyecto</label>
                                <button id="anadir-tarea" class="botones">Añadir tarea</button>
                            </div>
                        </div>
                        <form id="formulario-anadir" action="<?php echo $seccion; ?>" method="post" enctype="multipart/form-data">
                            <select id="id_tareas_asociadas" class="select2" data-placeholder="Selecciona o crea una tarea" name="id_tareas_asociadas[]" multiple>
                                <option value=""></option>
                                <?php if (count($todasTareas) != 0) { ?>
                                    <?php foreach ($todasTareas as $tarea) { ?>
                                        <option value="<?php echo $tarea["id_tarea"] ?>" ><?php echo $tarea["nombre_tarea"]; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>

                            <div class="campo-formulario">
                                <input type="submit" value="Añadir tarea" name="enviar" class="botones">
                            </div>
                        </form>
                        <?php if (!empty($_SESSION["error_addTareasProyecto"])) { ?>
                            <p class="texto-error"><?php echo $_SESSION["error_addTareasProyecto"] ?></p>
                        <?php } ?>

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
                            <th>Opciones</th>
                            </thead>
                            <?php foreach ($tareas as $t) { ?>
                                <tr>
                                    <td><?php echo $t["nombre_tarea"] ?></a></td>
                                    <td class="fecha-limite"><?php echo $t["fecha_limite_tarea"] ?? "No tiene" ?></td>
                                    <td><?php echo $t["nombre_etiqueta"] ?></td>
                                    <td><a href="/tareas/editar/<?php echo $t["id_tarea"] ?>" class="botones boton-anadir"><i class="fa-solid fa-pen"></i></a>
                                        <a href="/tareas/borrar/<?php echo $t["id_tarea"] ?>" class="botones boton-anadir"><i class="fa-solid fa-trash"></i></a></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else { ?>
                        <p>No hay tareas asociadas a este proyecto</p>
                    <?php } ?>
                    <p class="enlace-imagen-perfil"><?php foreach ($usuarios as $u) { ?>
                            <a href="/perfil/<?php echo $u["id_usuario"] ?> " class="enlace-imagen-perfil"><img src="/assets/img/usuarios/avatar-<?php echo $u["id_usuario"] ?>" alt="Avatar usuario <?php echo $u["username"] ?>" class='imagen-perfil-pequena'><?php echo $u["username"] ?></a>
                        <?php }
                        ?></p>
                    <p>Descripción del proyecto: </p>
                    <p><?php echo ($proyecto["descripcion_proyecto"] == "") ? "No tiene descripción" : $proyecto["descripcion_proyecto"] ?></p>
                    <?php if ($proyecto["editable"] == 1) { ?>
                        <div class="botones-proyecto-tarea">
                            <a href="/proyectos" class="botones"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                            <a href="/proyectos/borrar/<?php echo $proyecto["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                            <a href="/proyectos/editar/<?php echo $proyecto["id_proyecto"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                        </div>
                    <?php } else { ?>
                        <div class="botones-proyecto-tarea">
                            <a href="/proyectos" class="botones"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <script src="plugins/jquery/jquery.min.js"></script>
            <!-- Select2 -->
            <script src="plugins/select2/js/select2.full.min.js"></script>
            <script src="plugins/select2/js/i18n/es.js"></script>
            <script src="assets/js/admin/pages/main.js"></script>

            <script src="assets/js/public/fechasTareasProyectos.js"></script>

            <script>
                $("#anadir-tarea").on("click", function () {
                    $("#formulario-anadir").css({"display": "block"});
                    $("#anadir-tarea").css({"display": "none"});
                    $("#id_tareas_asociadas").css({"textAlign": "center"});
                });
            </script>
            <script>
                $(function () {
                    $("#id_tareas_asociadas").select2({
                        tags: true
                    });
                });
            </script>
        </main> <!-- Continua en plantillas/footer -->
