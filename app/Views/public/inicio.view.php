<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $titulo ?> | TaskVelocity</title>
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosInicio.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <script src="https://kit.fontawesome.com/e260e3cde1.js" crossorigin="anonymous"></script>
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
                    <?php if (isset($_SESSION["usuario"])) { ?>
                        <a href="/perfil/<?php echo $_SESSION["usuario"]["id_usuario"] ?>" class="enlace-perfil">
                            <img src="/assets/img/usuarios/avatar-<?php echo $_SESSION["usuario"]["id_usuario"] ?>" alt="Avatar usuario <?php echo $_SESSION["usuario"]["username"] ?>">
                            <p><?php echo $_SESSION["usuario"]["username"] ?></p>
                        </a>
                    </div>
                    <a href="/logout" class="botones botones-header"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>
                <?php } else { ?>
                    <a href="/login" class="botones botones-header">Iniciar sesión</a>
                <?php } ?>
            </div>
        </div>
    </header>
    <main>
        <div id="main-introduccion">
            <h1 class="apartados apartados-inicio">TaskVelocity</h1>
            <p>Organiza tu trabajo con este gestor de tareas y proyectos veloz.</p>
            <p>Haciendo click a uno los botones puedes empezar hoy a usarlo:</p>
            <div id="contenedor-botones-inicio">
                <a href="/register" class="botones botones-inicio">Crea cuenta ahora</a>
                <p>o</p>
                <a href="/login" class="botones botones-inicio">Inicia sesión</a>
            </div>
        </div>

        <div id="div-caracteristicas">
            <h2 class="apartados apartados-inicio">Características principales</h2>
            <p>Tiene las siguientes caracteristicas:</p>
            <div id="main-caracteristicas">
                <div class="imagen-caracteristicas">
                    <img src="assets/img/ejemplo-proyectos.png" alt="Ejemplo Proyectos"></img>
                </div>
                <div id="caracteristica-proyectos" class="caracteristicas-texto">
                    <h3>Gestor de proyectos</h3>
                    <p>Donde puedes ver todos los proyectos, crear uno con una fecha límite 
                        y añadir más miembros</p>
                </div>
                <div id="caracteristica-tareas" class="caracteristicas-texto">
                    <h3>Gestor de tareas</h3>
                    <p>Donde pueden tener distinto color según lo prefiera el usuario, 
                        una fecha límite, un proyecto asociado y una imagen y añadir a más personas</p>
                </div>
                <div class="imagen-caracteristicas">
                    <img src="assets/img/ejemplo-tareas.png" alt="Ejemplo Tareas"></img>
                </div>

                <div class="imagen-caracteristicas">
                    <img src="assets/img/ejemplo-perfil.png" alt="Ejemplo Perfil"></img>
                </div>
                <div id="caracteristica-perfil" class="caracteristicas-texto">
                    <h3>Tu perfil</h3>
                    <p>Donde puedes ver y editar tu perfil a tu gusto</p>
                </div>
            </div>
        </div>

        <div class="reseñas">
            <h2 class="apartados apartados-inicio">Reseñas y opiniones</h2>
            <div id="resenas-grid">
                <div class="resena"><img src="assets/img/usuarios/avatar-10" class="imagenes-pequenas imagenes-resenas"></img><p>Esta herramienta es fácil de usar y es útil para gestionar el día a día - Pablo</p></div>
                <div class="resena"><img src="assets/img/usuarios/avatar-1" class="imagenes-pequenas imagenes-resenas"></img><p>Esta herramienta es minimalista y simple que ayuda a gestionar los proyectos y tareas facilmente - Anabel</p></div>
                <div class="resena"><img src="assets/img/usuarios/avatar-11" class="imagenes-pequenas imagenes-resenas"></img><p>Esta aplicación me ayuda a gestionar mi día a día en el trabajo - Pepita</p></div>
                <div class="resena"><img src="assets/img/usuarios/avatar-23" class="imagenes-pequenas imagenes-resenas"></img><p>Esta aplicación ayuda en el trabajo a gestionar nuestros futuros proyectos - Antonio<p></div>
            </div>
        </div>
    </main> <!-- Continua en plantillas/footer -->
