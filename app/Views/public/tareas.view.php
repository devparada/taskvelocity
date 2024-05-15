<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | Tus tareas</title>
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareas.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
        <!-- Moment -->
        <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/locale/es.js"></script>
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/" class="logo-enlace">
                    <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
                    <h2>TaskVelocity</h2>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="/proyectos">Proyectos</a></li>
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
            <div id="introduccion">
                <h1>Tus tareas</h1>
                <div>
                    <?php foreach ($etiquetas as $etiqueta) { ?>
                        <a class="botones botones-filtros" href="/tareas?etiqueta=<?php echo $etiqueta["id_etiqueta"] ?>"><?php echo $etiqueta["nombre_etiqueta"] ?></a>
                    <?php } ?>
                    <a class="botones botones-filtros" href="/tareas">Mostrar todas</a>
                </div>
                <a href="/tareas/crear" class="botones">Crear una tarea</a>
            </div>
            <?php if (isset($informacion)) { ?>
                <div class="alerta-<?php echo ($informacion["estado"] == "success" ? "success" : "danger") ?>">
                    <p><?php echo $informacion["texto"] ?></p>
                </div>
            <?php } ?>
            <?php if (empty($tareas) && $_SERVER["REQUEST_URI"] == "/tareas") { ?>
                <div class="informacion">
                    <p><i class="fa-solid fa-circle-info"></i> Crea tu primera tarea pulsando en el botón Crear una tarea</p>
                </div>
            <?php } else if ((empty($tareas)) && $_SERVER["REQUEST_URI"] != "/tareas") { ?>
                <div class="informacion informacion-warning">
                    <p><i class="fa-solid fa-circle-info"></i> No se han encontrado tareas con esta etiqueta</p>
                </div>
            <?php } ?>
            <div id="tareas-grid">
                <?php foreach ($tareas as $proyecto => $value) { ?>
                    <div class="columnas">
                        <h2><?php echo $proyecto ?></h2>
                        <?php for ($i = 0; $i < count($value); $i++) { ?>
                            <div class="tarjetas"  id="<?php echo $value[$i]["id_tarea"] ?>" style="background-color: <?php echo $value[$i]["valor_color"] ?>">
                                <div class="informacion-tarea">
                                    <p>Tarea: <?php echo $value[$i]["nombre_tarea"] ?></p>
                                    <p>Etiqueta: <?php echo $value[$i]["nombre_etiqueta"] ?></p>
                                    <p class="fecha-limite"><?php echo $value[$i]["fecha_limite_tarea"] ?></p>
                                    <p>Propietario: <?php echo isset($value[$i]["id_usuario_tarea_prop"]) && ($value[$i]["id_usuario_tarea_prop"] == $_SESSION["usuario"]["id_usuario"]) ? "Tú" : $value[$i]["username"] ?></p>
                                    <div class="botones-tareas">
                                        <a href="/tareas/editar/<?php echo $value[$i]["id_tarea"] ?>" class="botones"><i class="fa-solid fa-pen"></i> Editar</a>
                                        <a href="/tareas/borrar/<?php echo $value[$i]["id_tarea"] ?>" class="botones"><i class="fa-solid fa-trash"></i> Borrar</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <script>
                moment.locale('es');
                for (var i = 0; i < document.getElementsByClassName("fecha-limite").length; i++) {
                    if (moment([document.getElementsByClassName("fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow() !== "Fecha inválida") {
                        document.getElementsByClassName("fecha-limite")[i].innerHTML = "Fecha límite: " + moment([document.getElementsByClassName("fecha-limite")[i].innerText], "YYYY-MM-DD").fromNow();
                    } else {
                        document.getElementsByClassName("fecha-limite")[i].innerHTML = "Fecha límite: No tiene";
                    }
                }

                const tarjetas = document.getElementsByClassName("tarjetas");

                for (var i = 0; i < tarjetas.length; i++) {
                    (function (i) {
                        tarjetas[i].addEventListener("click", function () {
                            window.location.href = "/tareas/ver/" + tarjetas[i].id;
                        });
                    })(i);
                }
            </script>
        </main> <!-- Continua en plantillas/footer -->
