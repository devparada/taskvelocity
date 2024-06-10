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
        <link rel="stylesheet" href="assets/css/public/estilosContacto.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                    <li><a href="/tareas">Tareas</a></li>
                    <li><a href="/contacto" class="apartado-activo">Contacto</a></li>
                </ul>
            </nav>
            <div id="perfil-cerrar">
                <div id="perfil">
                    <?php if (isset($_SESSION["usuario"])) { ?>
                        <a href="/perfil/<?php echo $_SESSION["usuario"]["id_usuario"] ?>?v=<?php echo time() ?>" class="enlace-perfil">
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
        <h1 class="apartados apartados-inicio">Contacto</h1>
        <div id="contacto">
            <h2>Preguntas frecuentes</h2>
            <div id="contenedor-preguntas">
                <ul>
                    <div class="pregunta">
                        <li>¿Porqué existe el proyecto personal con tu nombre de usuario?</li>
                        <li>El proyecto personal por defecto existe debido a que cuando se crea una tarea puede que no haya algún proyecto creado así que se utiliza este por defecto</li>
                    </div>
                    <div class="pregunta">
                        <li>¿Porqué al crear una tarea es necesario selecionar un color?</li>
                        <li>El color de la tarea se utiliza para decorar el fondo de la tarea de un color</li>
                    </div>
                    <div class="pregunta">
                        <li>¿Cómo elimino mi cuenta?</li>
                        <li>Haces click a la imagen de tu perfil y le das al botón Eliminar cuenta, confirmas que quieres eliminar la cuenta y se elimina.</li>
                    </div>
                </ul>
            </div>
        </div>
        <h2 id="formas-contacto-titulo">Formas de contacto</h2>
        <div id="formas-contacto-mapa-contenedor">
            <div id="formas-contacto">
                <p><i class="fa-solid fa-envelope"></i><a href="mailto:email@personal.com">email@personal.com</a></p>
                <p><i class="fa-solid fa-phone"></i><a href="tel:+34123456789">+34 123 45 67 89</a></p>
            </div>
            <div id="mapa">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d739.9035887625096!2d-8.616842279117405!3d42.11572945449898!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd2585acc976b0eb%3A0xbef71e73e4275846!2sR%C3%BAa%20Paralela%204%2C%20122%2C%2036475%2C%20Pontevedra!5e0!3m2!1ses!2ses!4v1717488165420!5m2!1ses!2ses" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </main> <!-- Continua en plantillas/footer -->
