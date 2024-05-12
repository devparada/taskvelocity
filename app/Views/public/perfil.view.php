<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <title>TaskVelocity | Tu perfil</title>
        <!-- Estilos propios -->
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
        <link rel="stylesheet" href="assets/css/public/estilosPerfil.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <script src="https://kit.fontawesome.com/e2a74f45d0.js" crossorigin="anonymous"></script>
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
                    <li><a href="/proyectos" class="botones">Proyectos</a></li>
                    <li><a href="/tareas" class="botones">Tareas</a></li>
                    <li><a href="/contacto" class="botones">Contacto</a></li>
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
                <h1><?php echo $titulo ?></h1>
                <a href="/perfil/editar/<?php echo $usuario["id_usuario"] ?>" class="botones">Editar</a>
            </div>
            <div id="contenido-principal">
                <div id="imagen-editar">
                    <img src="/assets/img/usuarios/avatar-<?php echo $_SESSION["usuario"]["id_usuario"] ?>" alt="Avatar usuario <?php echo $_SESSION["usuario"]["username"] ?>" id="imagen-perfil">
                </div>
                <div id="informacion-usuario">
                    <h2 class="apartados-inicio">Tus datos</h2>
                    <p>Tu nombre de usuario es: <?php echo $usuario["username"] ?></p>
                    <p>Tu correo electrónico es: <?php echo $usuario["email"] ?></p>
                    <p>Tu fecha de nacimiento es: <?php echo $usuario["fecha_nacimiento"] ?></p>
                    <p>Tu color favorito es: <span style="background-color: <?php echo $usuario["valor_color"] ?>" class="color-circulo"></span> <?php echo $usuario["nombre_color"] ?></p>
                    <p>Tu descripción es: <?php echo $usuario["descripcion_usuario"]; ?></p>
                    <h2 class="apartados-inicio">Estadísticas</h2>
                    <p>Tienes las siguientes estadísticas:</p>
                    <div id="contenedor-estadisticas-usuario">
                        <div class="estadistica-usuario">
                            <p>Proyectos</p>
                            <p><?php echo $proyecto ?></p>
                        </div>
                        <div class="estadistica-usuario">
                            <p>Tareas</p>
                            <p><?php echo $tarea ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </main> <!-- Continua en plantillas/footer -->
