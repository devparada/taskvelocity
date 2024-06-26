<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TaskVelocity | <?php echo $titulo ?></title>
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
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
                        <img src="/assets/img/usuarios/avatar-<?php echo $_SESSION["usuario"]["id_usuario"] ?>?v=<?php echo time() ?>" alt="Avatar usuario <?php echo $_SESSION["usuario"]["username"] ?>">
                        <p><?php echo $_SESSION["usuario"]["username"] ?></p>
                    </a>
                </div>
                <a href="/logout" class="botones botones-header"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>
            </div>
        </header>
        <main>
            <div id="introduccion">
                <h1>Tus proyectos</h1>
                <a href="/proyectos/crear" class="botones"><i class="fa-solid fa-circle-plus"></i> Crear un proyecto</a>
            </div>

            <?php if (isset($informacion)) { ?>
                <div class="alerta-div alerta-<?php echo ($informacion["estado"] == "success" ? "success" : "danger") ?>">
                    <p><?php echo $informacion["texto"] ?></p>
                </div>
            <?php } ?>
            <div id="proyectos-contenedor">
            <div>

            <script src="plugins/jquery/jquery.min.js"></script>
            <script>
                function cargarProyectos() {
                    const divProyecto = $('#proyectos-contenedor');

                    $.ajax({
                        url: "/async/proyectos",
                        success: function (response) {
                            // Insertar el HTML obtenido en el contenedor                            
                            divProyecto.html(response);

                            var proyectos = $(".proyectos");

                            for (let i = 0; i < proyectos.length; i++) {
                                // Al hacer click en el proyecto va a la siguiente url
                                // ! Sólo funciona con addEventListener() no funciona con on() de JQuery
                                proyectos[i].addEventListener("click", function () {
                                    window.location.href = "/proyectos/ver/" + proyectos[i].id;
                                });
                            }

                            let script = $("<script>", {
                                src: "assets/js/public/fechasTareasProyectos.js",
                                id: "scriptFechas"
                            });

                            $("body").append(script);

                            let scriptFechas = $("#scriptFechas");
                            // Se compruba si el script existe en el HTML
                            if (scriptFechas.length > 0) {
                                scriptFechas.remove();
                            }
                        }
                    });
                }

                cargarProyectos();
                setInterval(cargarProyectos, 5000);
            </script>
        </main> <!-- Continua en plantillas/footer -->
