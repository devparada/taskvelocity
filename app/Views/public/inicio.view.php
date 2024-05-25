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
                    <a href="/logout" class="botones"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</a>
                <?php } else { ?>
                    <a href="/login" class="botones">Iniciar sesión</a>
                <?php } ?>
            </div>
        </div>
    </header>
    <main>
        <div id="main-introduccion">
            <h1 class="apartados apartados-inicio">TaskVelocity</h1>
            <p>TaskVelocity es una aplicación web que es un gestor de tareas y proyectos veloz.</p>
            <p>Con solo crear una cuenta tienes acceso a este gestor de tareas y proyectos.</p>
            <p>Puedes crear una cuenta o inciar sesión haciendo click a los siguientes botones:</p>
            <div id="contenedor-botones-inicio">
                <a href="#" class="botones botones-inicio">Crear cuenta</a>
                <a href="#" class="botones botones-inicio">Iniciar sesión</a>
            </div>
        </div>

        <div id="div-caracteristicas">
            <h2 class="apartados apartados-inicio">Características</h2>
            <p>En este carousel se indican las características:</p>
            <div id="carousel" class="carousel slide">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Imagen 1"></button>
                    <button type="button" data-bs-target="#carousel" data-bs-slide-to="1" aria-label="Imagen 2"></button>
                    <button type="button" data-bs-target="#carousel" data-bs-slide-to="2" aria-label="Imagen 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="assets/img/ejemplo-proyectos.png" class="d-block w-100" alt="Imagen proyectos">
                        <div class="carousel-caption d-flex align-items-center justify-content-center">
                            <div class="texto-carousel">
                                <p class="titulo-carousel">Gestor de proyectos</p>
                                <p>Donde ver todos los proyectos,crear un proyecto con una fecha límite y una imagen 
                                    y añadir más miembros para trabajar en compañia</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="assets/img/ejemplo-tareas.png" class="d-block w-100" alt="Imagen tareas">
                        <div class="carousel-caption d-md-block">
                            <div class="texto-carousel">
                                <p class="titulo-carousel">Gestor de tareas</p>
                                <p>Donde las tareas pueden tener distinto color según lo prefiera el usuario, 
                                    una fecha límite, un proyecto asociado y una imagen y añadir a más personas para trabjar juntos</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="assets/img/ejemplo-perfil.png" class="d-block w-100" alt="Imagen perfil">
                        <div class="carousel-caption d-md-block">
                            <div class="texto-carousel">
                                <p class="titulo-carousel">Tú perfil</p>
                                <p>Donde puedes ver y editar tu perfil a tu gusto como por ejemplo cambiando el color favorito, 
                                    el correo electrónico, la imagen y la descripción</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        </div>
    </main> <!-- Continua en plantillas/footer -->
