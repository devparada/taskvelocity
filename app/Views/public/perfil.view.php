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
        <link rel="stylesheet" href="assets/css/public/estilosPerfil.css">
        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo.png">
        <!-- Iconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                <a href="/logout" class="botones botones-header"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>
            </div>
        </header>
        <main>
            <div id="introduccion">
                <h1><?php echo $titulo ?></h1>
            </div>
            <div id="contenido-principal">
                <div id="imagen-editar">
                    <img src="/assets/img/usuarios/avatar-<?php echo $idUsuario ?>" alt="Avatar usuario <?php echo $usuario["username"] ?>" id="imagen-perfil">
                    <div id="informacion-adicional">
                        <?php if ($_SESSION["usuario"]["id_usuario"] == $idUsuario) { ?>
                            <?php if ($usuario["fecha_nacimiento"]) { ?>
                                <p><i class="fa-solid fa-cake-candles"></i> <?php
                                    setlocale(LC_TIME, 'es_ES.UTF-8');
                                    $fechaUsuario = new DateTimeImmutable($usuario["fecha_usuario_creado"]);
                                    $fechaFormateada = strftime("%e de %B", $fechaUsuario->getTimestamp());
                                    // Traduce los meses
                                    $fechaFormateadaDefinitiva = str_replace(array_keys($meses), array_values($meses), $fechaFormateada);
                                    echo $fechaFormateadaDefinitiva;
                                    ?></p>
                            <?php } ?>
                            <p><i class="fa-solid fa-user-plus"></i> <?php
                                setlocale(LC_TIME, 'es_ES.UTF-8');
                                $fechaUsuario = new DateTimeImmutable($usuario["fecha_usuario_creado"]);
                                $fechaFormateada = strftime("%e de %B", $fechaUsuario->getTimestamp());
                                // Traduce los meses
                                $fechaFormateadaDefinitiva = str_replace(array_keys($meses), array_values($meses), $fechaFormateada);
                                echo $fechaFormateadaDefinitiva;
                                ?></p>
                        <?php } ?>
                        <p>Color favorito: <span style="background-color: <?php echo $usuario["valor_color"] ?>" class="color-circulo"></span> <?php echo $usuario["nombre_color"] ?></p>
                    </div>
                </div>
                <div id="informacion-usuario">
                    <h2 class="apartados-inicio">Hola, <?php echo ($_SESSION["usuario"]["id_usuario"] != $usuario["id_usuario"]) ? "estás viendo el perfil de " : "" ?> <?php echo $usuario["username"] ?></h2>
                    <?php if ($_SESSION["usuario"]["id_usuario"] == $idUsuario) { ?>
                        <p>Tú correo electrónico es: <?php echo $usuario["email"] ?></p>
                    <?php } ?>
                    <?php if ($usuario["descripcion_usuario"] != "") { ?>
                        <p><?php echo ($_SESSION["usuario"]["id_usuario"] == $usuario["id_usuario"]) ? "Tú" : "Su" ?> descripción es:</p> 
                        <p><?php echo $usuario["descripcion_usuario"]; ?></p>
                    <?php } else { ?>
                        <p>No tienes una descripción</p>
                    <?php } ?>
                    <?php if ($_SESSION["usuario"]["id_usuario"] == $idUsuario) { ?>
                        <div id="estadistica-usuario-apartado">
                            <h2 class="apartados-inicio">Estadísticas</h2>
                            <div id="contenedor-estadisticas-usuario" class="<?php echo $idUsuario ?>">
                                <div class="estadistica-usuario" id="propietario-proyectos">
                                    <p><?php echo ($_SESSION["usuario"]["id_usuario"] == $usuario["id_usuario"]) ? "Eres" : "Es" ?> propietario de </p>
                                    <p> <?php echo $proyectoPropietario ?> proyectos</p>
                                </div>
                                <div class="estadistica-usuario" id="propietario-tareas">
                                    <p><?php echo ($_SESSION["usuario"]["id_usuario"] == $usuario["id_usuario"]) ? "Eres" : "Es" ?> propietario de </p>
                                    <p> <?php echo $tareaPropietario ?> tareas</p>
                                </div>

                                <div class="estadistica-usuario estadistica-usuario-clickable" style="background-color: <?php echo $etiquetas[0]["color_etiqueta"] ?>" id="etiqueta-pendiente">
                                    <p><?php echo ($_SESSION["usuario"]["id_usuario"] == $usuario["id_usuario"]) ? "Tienes" : "Tiene" ?></p>
                                    <p> <?php echo count($tareasPendientes) ?> tareas pendientes</p>
                                </div>

                                <div class="estadistica-usuario estadistica-usuario-clickable estadistica-usuario-contraste" style="background-color: <?php echo $etiquetas[1]["color_etiqueta"] ?>" id="etiqueta-progreso">
                                    <p><?php echo ($_SESSION["usuario"]["id_usuario"] == $usuario["id_usuario"]) ? "Tienes" : "Tiene" ?></p>
                                    <p> <?php echo count($tareasProgresos) ?> tareas en progreso</p>
                                </div>

                                <div class="estadistica-usuario estadistica-usuario-clickable estadistica-usuario-contraste" style="background-color: <?php echo $etiquetas[2]["color_etiqueta"] ?>" id="etiqueta-finalizada">
                                    <p><?php echo ($_SESSION["usuario"]["id_usuario"] == $usuario["id_usuario"]) ? "Tienes" : "Tiene" ?></p>
                                    <p> <?php echo count($tareasFinalizadas) ?> tareas finalizadas</p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div id="informacion-pie-contenedor">
                        <h2>Opciones</h2>
                        <div id="informacion-pie">
                            <?php if ($_SESSION["usuario"]["id_usuario"] == $idUsuario) { ?>
                                <button type="button" class="botones botones-perfil" data-bs-toggle="modal" data-bs-target="#modalBorrarUsuario"id="boton-borrar"><i class="fa-solid fa-user-minus"></i> Borrar cuenta</button>
                                <button id="boton-editar" onclick="window.location.href = '/perfil/editar/<?php echo $_SESSION["usuario"]["id_usuario"] ?>'" class="botones botones-perfil"><i class="fa-solid fa-user-pen"></i> Editar perfil</button>        
                            <?php } else { ?>
                                <a id="boton-editar" href="/proyectos" class="botones botones-perfil"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="modalBorrarUsuario" tabindex="-1" aria-labelledby="modalBorrarUsuario" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fs-5">Borrar cuenta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de borrar tu cuenta?</p>
                            <p>Ten en cuenta que se borran también tus proyectos y tareas</p>
                        </div>
                        <div class="modal-footer">
                            <a id="boton-borrar" href="perfil/borrar/<?php echo $idUsuario ?>" class="botones alerta-danger"><i class="fa-solid fa-user-minus"></i> Borrar cuenta</a>
                            <a class="botones" data-bs-dismiss="modal">Cerrar</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($_SESSION["usuario"]["id_usuario"] == $idUsuario) { ?>
                <script>
                    const estadisticasClickable = document.querySelectorAll(".estadistica-usuario-clickable");

                    for (var i = 0; i < estadisticasClickable.length; i++) {
                        estadisticasClickable[i].style.cursor = "pointer";
                    }

                    document.querySelector("#etiqueta-pendiente").addEventListener("click", function () {
                        window.location.href = "/tareas?etiqueta=1";
                    });
                    document.querySelector("#etiqueta-progreso").addEventListener("click", function () {
                        window.location.href = "/tareas?etiqueta=2";
                    });
                    document.querySelector("#etiqueta-finalizada").addEventListener("click", function () {
                        window.location.href = "/tareas?etiqueta=3";
                    });
                </script>
            <?php } ?>
        </main> <!-- Continua en plantillas/footer -->
