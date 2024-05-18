<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $titulo ?> | TaskVelocity</title>
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
                <h1><?php echo $titulo ?></h1>
            </div>
            <div id="contenido-principal">
                <div id="imagen-editar">
                    <img src="/assets/img/usuarios/avatar-<?php echo $idUsuario ?>" alt="Avatar usuario <?php echo $idUsuario ?>" id="imagen-perfil">
                    <div id="informacion-adicional">
                        <?php if ($usuario["fecha_nacimiento"]) { ?>
                            <p><i class="fa-solid fa-cake-candles"></i> <?php
                                setlocale(LC_TIME, 'es_ES.UTF-8');
                                $fechaNacimiento = new DateTimeImmutable($usuario["fecha_nacimiento"]);
                                echo strftime("%e de %B", $fechaNacimiento->getTimestamp())
                                ?></p>
                        <?php } ?>
                        <p><i class="fa-solid fa-user-plus"></i> <?php
                            setlocale(LC_TIME, 'es_ES.UTF-8');
                            $fechaUsuario = new DateTimeImmutable($usuario["fecha_usuario_creado"]);
                            echo strftime("%e de %B", $fechaUsuario->getTimestamp())
                            ?></p>
                        <p>Color favorito: <span style="background-color: <?php echo $usuario["valor_color"] ?>" class="color-circulo"></span> <?php echo $usuario["nombre_color"] ?></p>
                    </div>
                </div>
                <div id="informacion-usuario">
                    <h2 class="apartados-inicio">Hola, <?php echo $usuario["username"] ?></h2>
                    <p>Tu correo electrónico es: <?php echo $usuario["email"] ?></p>                   
                    <?php if ($usuario["descripcion_usuario"] != "") { ?>
                        <p>Tu descripción es:</p> 
                        <p><?php echo $usuario["descripcion_usuario"]; ?></p>
                    <?php } else { ?>
                        <p>No tienes una descripción</p>
                    <?php } ?>
                    <div id="estadistica-usuario-apartado">
                        <h2 class="apartados-inicio">Estadísticas</h2>
                        <div id="contenedor-estadisticas-usuario">
                            <div class="estadistica-usuario">
                                <p>Eres propietario de </p>
                                <p> <?php echo $proyectoPropietario ?> proyectos</p>
                            </div>
                            <div class="estadistica-usuario">
                                <p>Eres propietario de </p>
                                <p> <?php echo $tareaPropietario ?> tareas</p>
                            </div>

                            <div class="estadistica-usuario" style="background-color: <?php echo $etiquetas[0]["color_etiqueta"] ?>">
                                <p>Tienes </p>
                                <p> <?php echo count($tareasPendientes) ?> tareas pendientes</p>
                            </div>

                            <div class="estadistica-usuario estadistica-usuario-contraste" style="background-color: <?php echo $etiquetas[1]["color_etiqueta"] ?>">
                                <p>Tienes </p>
                                <p> <?php echo count($tareasProgresos) ?> tareas en progreso</p>
                            </div>

                            <div class="estadistica-usuario estadistica-usuario-contraste" style="background-color: <?php echo $etiquetas[2]["color_etiqueta"] ?>">
                                <p>Tienes </p>
                                <p> <?php echo count($tareasFinalizadas) ?> tareas finalizadas</p>
                            </div>
                        </div>
                    </div>
                    <div id="informacion-pie-contenedor">
                        <h2>Opciones</h2>
                        <div id="informacion-pie">
                            <?php if ($_SESSION["usuario"]["id_usuario"] == $idUsuario) { ?>
                                <a id="boton-borrar" href="perfil/borrar/<?php echo $idUsuario ?>" class="botones" id="boton-borrar"><i class="fa-solid fa-user-minus"></i> Borrar cuenta</a>
                                <a id="boton-editar" href="/perfil/editar/<?php echo $_SESSION["usuario"]["id_usuario"] ?>" class="botones"><i class="fa-solid fa-user-pen"></i> Editar perfil</a>
                            <?php } else { ?>
                                <a id="boton-editar" href="/proyectos" class="botones"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </main> <!-- Continua en plantillas/footer -->
