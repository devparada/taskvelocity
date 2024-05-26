<?php

namespace Com\TaskVelocity\Core;

use Steampixel\Route;

class FrontController {

    static function main() {

        // Rutas que requieren login para acceder
        if (isset($_SESSION["usuario"])) {
            if (strpos($_SESSION["permisos"]["inicio"], "r") !== false) {
                Route::add('/admin',
                        function () {
                            $controlador = new \Com\TaskVelocity\Controllers\InicioController();
                            $controlador->indexAdmin();
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["usuarios"], "r") !== false) {
                Route::add('/admin/usuarios',
                        function () {
                            $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                            $controlador->mostrarUsuarios();
                        }
                        , 'get');

                Route::add('/admin/usuarios/view/([0-9]+)',
                        function ($idUsuario) {
                            $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                            $controlador->verUsuarioAdmin($idUsuario);
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["usuarios"], "w") !== false) {
                Route::add('/admin/usuarios/add',
                        function () {
                            $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                            $controlador->mostrarAddUsuario();
                        }
                        , 'get');

                Route::add('/admin/usuarios/add',
                        function () {
                            $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                            $controlador->procesarAddUsuario();
                        }
                        , 'post');

                Route::add('/admin/usuarios/edit/([0-9]+)',
                        function ($idUsuario) {
                            $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                            $controlador->mostrarEdit($idUsuario);
                        }
                        , 'get');

                Route::add('/admin/usuarios/edit/([0-9]+)',
                        function ($idUsuario) {
                            $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                            $controlador->procesarEdit($idUsuario);
                        }
                        , 'post');
            }

            if (strpos($_SESSION["permisos"]["usuarios"], "d") !== false) {
                Route::add('/admin/usuarios/delete/([0-9]+)',
                        function ($idUsuario) {
                            $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                            $controlador->procesarDelete($idUsuario);
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["tareas"], "r") !== false) {
                Route::add('/admin/tareas',
                        function () {
                            $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                            $controlador->mostrarTareas();
                        }
                        , 'get');

                Route::add('/admin/tareas/view/([0-9]+)',
                        function ($idTarea) {
                            $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                            $controlador->verTarea($idTarea);
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["tareas"], "w") !== false) {
                Route::add('/admin/tareas/add',
                        function () {
                            $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                            $controlador->mostrarAdd();
                        }
                        , 'get');

                Route::add('/admin/tareas/add',
                        function () {
                            $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                            $controlador->procesarAdd();
                        }
                        , 'post');

                Route::add('/admin/tareas/edit/([0-9]+)',
                        function ($idTarea) {
                            $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                            $controlador->mostrarEdit($idTarea);
                        }
                        , 'get');

                Route::add('/admin/tareas/edit/([0-9]+)',
                        function ($idTarea) {
                            $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                            $controlador->procesarEdit($idTarea);
                        }
                        , 'post');
            }


            if (strpos($_SESSION["permisos"]["tareas"], "d") !== false) {
                Route::add('/admin/tareas/delete/([0-9]+)',
                        function ($idTarea) {
                            $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                            $controlador->procesarDelete($idTarea);
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["proyectos"], "r") !== false) {
                Route::add('/admin/proyectos',
                        function () {
                            $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                            $controlador->mostrarProyectos();
                        }
                        , 'get');

                Route::add('/admin/proyectos/view/([0-9]+)',
                        function ($idProyecto) {
                            $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                            $controlador->verProyecto($idProyecto);
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["proyectos"], "w") !== false) {
                Route::add('/admin/proyectos/add',
                        function () {
                            $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                            $controlador->mostrarAdd();
                        }
                        , 'get');

                Route::add('/admin/proyectos/add',
                        function () {
                            $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                            $controlador->procesarAdd();
                        }
                        , 'post');

                Route::add('/admin/proyectos/edit/([0-9]+)',
                        function ($idProyecto) {
                            $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                            $controlador->mostrarEdit($idProyecto);
                        }
                        , 'get');

                Route::add('/admin/proyectos/edit/([0-9]+)',
                        function ($idProyecto) {
                            $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                            $controlador->procesarEdit($idProyecto);
                        }
                        , 'post');
            }

            if (strpos($_SESSION["permisos"]["proyectos"], "d") !== false) {
                Route::add('/admin/proyectos/delete/([0-9]+)',
                        function ($idProyecto) {
                            $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                            $controlador->procesarDelete($idProyecto);
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["logs"], "r") !== false) {
                Route::add('/admin/logs',
                        function () {
                            $controlador = new \Com\TaskVelocity\Controllers\LogController();
                            $controlador->mostrarLogs();
                        }
                        , 'get');
            }

            // Rutas de proyectos
            Route::add('/proyectos',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                        $controlador->mostrarProyectos();
                    }
                    , 'get');

            Route::add('/proyectos/ver/([0-9]+)',
                    function ($idProyecto) {
                        $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                        $controlador->verProyecto($idProyecto);
                    }
                    , 'get');

            Route::add('/proyectos/ver/([0-9]+)',
                    function ($idProyecto) {
                        $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                        $controlador->procesarAddTareasProyecto($idProyecto);
                    }
                    , 'post');

            Route::add('/proyectos/crear',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                        $controlador->mostrarAdd();
                    }
                    , 'get');

            Route::add('/proyectos/crear',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                        $controlador->procesarAdd();
                    }
                    , 'post');

            Route::add('/proyectos/editar/([0-9]+)',
                    function ($idProyecto) {
                        $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                        $controlador->mostrarEdit($idProyecto);
                    }
                    , 'get');

            Route::add('/proyectos/editar/([0-9]+)',
                    function ($idProyecto) {
                        $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                        $controlador->procesarEdit($idProyecto);
                    }
                    , 'post');

            Route::add('/proyectos/borrar/([0-9]+)',
                    function ($idProyecto) {
                        $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                        $controlador->procesarDelete($idProyecto);
                    }
                    , 'get');

            // Rutas de tareas
            Route::add('/tareas',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                        $controlador->mostrarTareas();
                    }
                    , 'get');

            Route::add('/tareas/crear',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                        $controlador->mostrarAdd();
                    }
                    , 'get');

            Route::add('/tareas/crear',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                        $controlador->procesarAdd();
                    }
                    , 'post');

            Route::add('/tareas/editar/([0-9]+)',
                    function ($idTarea) {
                        $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                        $controlador->mostrarEdit($idTarea);
                    }
                    , 'get');

            Route::add('/tareas/editar/([0-9]+)',
                    function ($idTarea) {
                        $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                        $controlador->procesarEdit($idTarea);
                    }
                    , 'post');

            Route::add('/tareas/borrar/([0-9]+)',
                    function ($idTarea) {
                        $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                        $controlador->procesarDelete($idTarea);
                    }
                    , 'get');

            // Rutas de perfil
            Route::add('/perfil/([0-9]+)',
                    function ($idUsuario) {
                        $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                        $controlador->mostrarPerfil($idUsuario);
                    }
                    , 'get');

            Route::add('/perfil/editar/([0-9]+)',
                    function ($idUsuario) {
                        $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                        $controlador->mostrarEdit($idUsuario);
                    }
                    , 'get');

            Route::add('/perfil/editar/([0-9]+)',
                    function ($idUsuario) {
                        $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                        $controlador->procesarEdit($idUsuario);
                    }
                    , 'post');

            Route::add('/perfil/borrar/([0-9]+)',
                    function ($idUsuario) {
                        $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                        $controlador->procesarDelete($idUsuario);
                    }
                    , 'get');

            Route::add('/async/proyectos',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\ProyectoController();
                        $controlador->mostrarProyectosAsync();
                    }
                    , 'get');

            Route::add('/async/tareas',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\TareaController();
                        $controlador->mostrarTareasAsync();
                    }
                    , 'get');

            Route::add('/logout',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\InicioController();
                        $controlador->logout();
                    }
                    , 'get');

            // Rutas que los usuarios pueden acceder sin logearse
        } else {
            Route::add('/login',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                        $controlador->mostrarLogin();
                    }
                    , 'get');

            Route::add('/login',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                        $controlador->procesarLogin();
                    }
                    , 'post');

            Route::add('/register',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                        $controlador->mostrarRegister();
                    }
                    , 'get');

            Route::add('/register',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\UsuarioController();
                        $controlador->procesarAddUsuario();
                    }
                    , 'post');

            // Redirecciones cuando un usuario logeado intenta acceder a alguna sección
            Route::add('/proyectos',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\InicioController();
                        $controlador->restingidoRedireccion();
                    }
                    , 'get');

            Route::add('/proyectos/crear',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\InicioController();
                        $controlador->restingidoRedireccion();
                    }
                    , 'get');

            Route::add('/proyectos/editar/([0-9]+)',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\InicioController();
                        $controlador->restingidoRedireccion();
                    }
                    , 'get');

            Route::add('/tareas',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\InicioController();
                        $controlador->restingidoRedireccion();
                    }
                    , 'get');

            Route::add('/tareas/crear',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\InicioController();
                        $controlador->restingidoRedireccion();
                    }
                    , 'get');

            Route::add('/tareas/editar/([0-9]+)',
                    function () {
                        $controlador = new \Com\TaskVelocity\Controllers\InicioController();
                        $controlador->restingidoRedireccion();
                    }
                    , 'get');
        }

        Route::add('/',
                function () {
                    $controlador = new \Com\TaskVelocity\Controllers\InicioController();
                    $controlador->index();
                }
                , 'get');

        Route::pathNotFound(
                function () {
                    $controller = new \Com\TaskVelocity\Controllers\ErroresController();
                    $controller->error404();
                }
        );

        Route::methodNotAllowed(
                function () {
                    $controller = new \Com\TaskVelocity\Controllers\ErroresController();
                    $controller->error405();
                }
        );

        Route::run();
    }
}
