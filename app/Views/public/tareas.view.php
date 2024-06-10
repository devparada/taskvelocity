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
        <link rel="stylesheet" href="assets/css/public/estilosTareas.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectosTareas.css">
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
                <a href="/" class="logo-enlace">
                    <img src="assets/img/logo.png" alt="Logo de TaskVelocity" class="imagenes-pequenas">
                    <h1>TaskVelocity</h1>
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
                <a href="/logout" class="botones botones-header"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>
            </div>
        </header>
        <main>
            <div id="introduccion">
                <h1>Tus tareas</h1>
                <div>
                    <?php foreach ($etiquetas as $etiqueta) { ?>
                        <a class="botones botones-filtros" href="/tareas?etiqueta=<?php echo $etiqueta["id_etiqueta"] ?>" style="background-color:<?php echo $etiqueta["color_etiqueta"] ?>;color:<?php echo $etiqueta["color_letra"] ?>"><?php echo $etiqueta["nombre_etiqueta"] ?></a>
                    <?php } ?>
                    <a class="botones botones-filtros" href="/tareas">Mostrar todas</a>
                </div>
                <a href="/tareas/crear" class="botones"><i class="fa-solid fa-circle-plus"></i> Crear una tarea</a>
            </div>
            <?php if (isset($informacion)) { ?>
            </div>
            <div class="alerta-div alerta-<?php echo ($informacion["estado"] == "success" ? "success" : "danger") ?>">
                <p><?php echo $informacion["texto"] ?></p>
            </div>
        <?php } ?>
        <div id="contenedor-principal">
        </div>
        <script src="plugins/jquery/jquery.min.js"></script>
        <script>
            function ajaxTareas() {
                const divContenedor = $('#contenedor-principal');

                $.ajax({
                    url: "/async/tareas",
                    success: function (response) {
                        // Insertar el HTML obtenido en el contenedor
                        divContenedor.html(response);

                        let scriptFecha = $("<script>", {
                            src: "assets/js/public/fechasTareasProyectos.js",
                            id: "scriptFecha"
                        });

                        $("body").append(scriptFecha);

                        let scriptFechaScript = $("#scriptFecha");
                        // Se compruba si el script existe en el HTML
                        if (scriptFechaScript.length > 0) {
                            scriptFechaScript.remove();
                        }

                        const divTareas = $("#tareas-grid");

                        var inicialX, offsetX;

                        function moverTareas(evento) {
                            var distanciaX = evento.clientX - inicialX;
                            var nuevaPosicionX = offsetX - distanciaX;

                            // Establece la nueva posición del elemento
                            divTareas.scrollLeft(nuevaPosicionX);
                        }

                        divTareas.on("mousedown", function (evento) {
                            // Guarda la posición inicial del ratón y la posición inicial del elemento
                            inicialX = evento.clientX;
                            offsetX = divTareas.scrollLeft();

                            divTareas.on("mousemove", moverTareas);
                        });

                        $(document).on("mouseup", function () {
                            $(document).off("mousemove", moverTareas);
                        });
                    }
                });
            }

            ajaxTareas();
            setInterval(ajaxTareas, 5000);
        </script>
    </main> <!-- Continua en plantillas/footer -->
