<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TaskVelocity | <?php echo $titulo ?></title>
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosProyectos.css">
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

            <div id="introduccion">
                <h1>Tus proyectos</h1>
                <a href="/proyectos/crear" class="botones"><i class="fa-solid fa-circle-plus"></i> Crear un proyecto</a>
            </div>

            <?php if (isset($informacion)) { ?>
                <div class="alerta-<?php echo ($informacion["estado"] == "success" ? "success" : "danger") ?>">
                    <p><?php echo $informacion["texto"] ?></p>
                </div>
            <?php } ?>
            <div class="proyectos-grid" id="proyectos-grid"></div>

            <script>
                function cargarProyectos() {
                    const divProyecto = document.getElementById('proyectos-grid');

                    // Realizar la solicitud AJAX
                    let xhr = new XMLHttpRequest();
                    xhr.open('GET', '/async/proyectos', true);

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Insertar el HTML obtenido en el contenedor
                            divProyecto.innerHTML = xhr.responseText;

                            var proyectos = document.getElementsByClassName("proyectos");

                            for (let i = 0; i < proyectos.length; i++) {
                                // Al hacer click en el proyecto va a la siguiente url
                                proyectos[i].addEventListener("click", function () {
                                    console.log(proyectos[i]);
                                    window.location.href = "/proyectos/ver/" + proyectos[i].id;
                                });
                            }

                            const script = document.createElement('script');
                            script.src = 'assets/js/public/fechasTareasProyectos.js';
                            document.body.appendChild(script);
                        }
                    };

                    xhr.send();
                }

                cargarProyectos();
                setInterval(cargarProyectos, 10000);
            </script>
        </main> <!-- Continua en plantillas/footer -->
