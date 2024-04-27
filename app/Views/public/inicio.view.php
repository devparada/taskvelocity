<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>TaskVelocity | Inicio</title>
        <link rel="stylesheet" href="assets/css/public/estilosGeneral.css">
    </head>
    <body>
        <header>
            <div id="logo">
                <a href="/">
                    <img src="assets/img/logo.png" alt="Logo">
                </a>
                <p>TaskVelocity</p>
            </div>
            <?php if (isset($_SESSION["usuario"])) { ?>
                <a href="/proyectos" class="botones">Ir a tus proyectos</a>
            <?php } else { ?>
                <a href="/login" class="botones">Iniciar sesión</a>
            <?php } ?>
        </header>
        <main>
            <p>(segunda version)</p>
            <p>Bienvenido a TaskVelocity</p>
            <p>Gestor de tareas y proyectos veloz</p>
            <p>Primera versión de la página de inicio</p>
        </main>
        <footer>
            <p>TaskVelocity - 2024</p>
            <div id="iconos-footer">
                <a href="#">A</a>
                <a href="#">B</a>
                <a href="#">C</a>
                <a href="#">D</a>
            </div>
        </footer>
    </body>
</html>
