<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $titulo ?> | TaskVelocity</title>
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosTareas.css">
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
                <a href="/" class="logo-enlace">
                    <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
                    <h2>TaskVelocity</h2>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="/proyectos">Proyectos</a></li>
                    <li><a href="/tareas" class="apartado-activo">Tareas</a></li>
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
                <a href="/tareas/crear" class="botones"><i class="fa-solid fa-circle-plus"></i> Crear una tarea</a>
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
            </div>
            <script>
                function ajaxTareas() {
                    const divTareas = document.getElementById('tareas-grid');

                    // Realizar la solicitud AJAX
                    let xhr = new XMLHttpRequest();
                    xhr.open('GET', '/async/tareas', true);

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Insertar el HTML obtenido en el contenedor
                            divTareas.innerHTML = xhr.responseText;

                            const script = document.createElement('script');
                            script.src = 'assets/js/public/fechasTareasProyectos.js';
                            document.body.appendChild(script);
                        }
                    };

                    xhr.send();
                }

                ajaxTareas();
                setInterval(ajaxTareas, 10000);
            </script>

            <script>
                const contenedorTareas = document.getElementById("tareas-grid");

                var inicialX, offsetX;

                contenedorTareas.addEventListener("mousedown", function (evento) {
                    // Guarda la posición inicial del ratón y la posición inicial del elemento
                    inicialX = evento.clientX;
                    offsetX = contenedorTarjetas.scrollLeft;

                    contenedorTareas.addEventListener("mousemove", moverContenedor);
                });

                document.addEventListener("mouseup", function () {
                    contenedorTareas.removeEventListener("mousemove", moverContenedor);
                });

                function moverContenedor(evento) {
                    var distanciaX = evento.clientX - inicialX;
                    var nuevaPosicionX = offsetX - distanciaX;

                    // Establece la nueva posición del elemento
                    contenedorTareas.scrollLeft = nuevaPosicionX;
                }
            </script>

        </main> <!-- Continua en plantillas/footer -->
