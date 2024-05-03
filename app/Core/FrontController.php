<?php

namespace Com\Daw2\Core;

use Steampixel\Route;

class FrontController {

    static function main() {

        // Rutas que requieren login para acceder
        if (isset($_SESSION["usuario"])) {
            if (strpos($_SESSION["permisos"]["inicio"], "r") !== false) {
                Route::add('/admin',
                        function () {
                            $controlador = new \Com\Daw2\Controllers\InicioController();
                            $controlador->indexAdmin();
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["usuarios"], "r") !== false) {
                Route::add('/admin/usuarios',
                        function () {
                            $controlador = new \Com\Daw2\Controllers\UsuarioController();
                            $controlador->mostrarUsuarios();
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["usuarios"], "w") !== false) {
                Route::add('/admin/usuarios/add',
                        function () {
                            $controlador = new \Com\Daw2\Controllers\UsuarioController();
                            $controlador->mostrarAddUsuario();
                        }
                        , 'get');

                Route::add('/admin/usuarios/add',
                        function () {
                            $controlador = new \Com\Daw2\Controllers\UsuarioController();
                            $controlador->procesarAddUsuario();
                        }
                        , 'post');
            }

            if (strpos($_SESSION["permisos"]["usuarios"], "r") !== false) {
                Route::add('/admin/usuarios/view/([0-9]+)',
                        function ($idUsuario) {
                            $controlador = new \Com\Daw2\Controllers\UsuarioController();
                            $controlador->verUsuario($idUsuario);
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["usuarios"], "w") !== false) {
                Route::add('/admin/usuarios/edit/([0-9]+)',
                        function ($idUsuario) {
                            $controlador = new \Com\Daw2\Controllers\UsuarioController();
                            $controlador->mostrarEdit($idUsuario);
                        }
                        , 'get');

                Route::add('/admin/usuarios/edit/([0-9]+)',
                        function ($idUsuario) {
                            $controlador = new \Com\Daw2\Controllers\UsuarioController();
                            $controlador->procesarEdit($idUsuario);
                        }
                        , 'post');
            }

            if (strpos($_SESSION["permisos"]["usuarios"], "d") !== false) {
                Route::add('/admin/usuarios/delete/([0-9]+)',
                        function ($idUsuario) {
                            $controlador = new \Com\Daw2\Controllers\UsuarioController();
                            $controlador->procesarDelete($idUsuario);
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["tareas"], "r") !== false) {
                Route::add('/admin/tareas',
                        function () {
                            $controlador = new \Com\Daw2\Controllers\TareaController();
                            $controlador->mostrarTareas();
                        }
                        , 'get');
            }

            if (strpos($_SESSION["permisos"]["tareas"], "r") !== false) {
                Route::add('/admin/tareas/add',
                        function () {
                            $controlador = new \Com\Daw2\Controllers\TareaController();
                            $controlador->mostrarAdd();
                        }
                        , 'get');

                Route::add('/admin/tareas/add',
                        function () {
                            $controlador = new \Com\Daw2\Controllers\TareaController();
                            $controlador->procesarAdd();
                        }
                        , 'post');
            }
            if (strpos($_SESSION["permisos"]["tareas"], "r") !== false) {
                Route::add('/admin/tareas/delete/([0-9]+)',
                        function ($idTarea) {
                            $controlador = new \Com\Daw2\Controllers\TareaController();
                            $controlador->procesarDelete($idTarea);
                        }
                        , 'get');
            }
            if (strpos($_SESSION["permisos"]["proyectos"], "r") !== false) {
                Route::add('/admin/proyectos',
                        function () {
                            $controlador = new \Com\Daw2\Controllers\ProyectoController();
                            $controlador->mostrarProyectos();
                        }
                        , 'get');
            }
            if (strpos($_SESSION["permisos"]["proyectos"], "r") !== false) {
                Route::add('/admin/proyectos/add',
                        function () {
                            $controlador = new \Com\Daw2\Controllers\ProyectoController();
                            $controlador->mostrarAdd();
                        }
                        , 'get');

                Route::add('/admin/proyectos/add',
                        function () {
                            $controlador = new \Com\Daw2\Controllers\ProyectoController();
                            $controlador->procesarAdd();
                        }
                        , 'post');
            }

            if (strpos($_SESSION["permisos"]["proyectos"], "r") !== false) {
                Route::add('/admin/proyectos/delete/([0-9]+)',
                        function ($idProyecto) {
                            $controlador = new \Com\Daw2\Controllers\ProyectoController();
                            $controlador->procesarDelete($idProyecto);
                        }
                        , 'get');
            }

            // Rutas de usuarios
            Route::add('/proyectos',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\ProyectoController();
                        $controlador->mostrarProyectosPublic();
                    }
                    , 'get');

            Route::add('/proyectos/ver/([0-9]+)',
                    function ($idProyecto) {
                        $controlador = new \Com\Daw2\Controllers\ProyectoController();
                        $controlador->verProyectoPublic($idProyecto);
                    }
                    , 'get');

            Route::add('/proyectos/crear',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\ProyectoController();
                        $controlador->mostrarAdd();
                    }
                    , 'get');

            Route::add('/proyectos/crear',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\ProyectoController();
                        $controlador->procesarAdd();
                    }
                    , 'post');

            Route::add('/tareas',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\TareaController();
                        $controlador->mostrarTareas();
                    }
                    , 'get');

            Route::add('/logout',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\InicioController();
                        $controlador->logout();
                    }
                    , 'get');

            // Rutas que los usuarios pueden acceder sin logearse
        } else {
            Route::add('/login',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->mostrarLogin();
                    }
                    , 'get');

            Route::add('/login',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->procesarLogin();
                    }
                    , 'post');

            Route::add('/register',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->mostrarRegister();
                    }
                    , 'get');

            Route::add('/register',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->procesarAddUsuario();
                    }
                    , 'post');
        }

        Route::add('/',
                function () {
                    $controlador = new \Com\Daw2\Controllers\InicioController();
                    $controlador->index();
                }
                , 'get');

        Route::pathNotFound(
                function () {
                    $controller = new \Com\Daw2\Controllers\ErroresController();
                    $controller->error404();
                }
        );

        Route::methodNotAllowed(
                function () {
                    $controller = new \Com\Daw2\Controllers\ErroresController();
                    $controller->error405();
                }
        );

        Route::run();
    }
}
