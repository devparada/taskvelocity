<?php

namespace Com\Daw2\Core;

use Steampixel\Route;

class FrontController {

    static function main() {

        if (isset($_SESSION["usuario"])) {
            Route::add('/admin',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\InicioController();
                        $controlador->indexAdmin();
                    }
                    , 'get');

            Route::add('/logout',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\InicioController();
                        $controlador->logout();
                    }
                    , 'get');

            Route::add('/admin/usuarios',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->mostrarUsuarios();
                    }
                    , 'get');

            Route::add('/admin/usuarios/add',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->mostrarAdd();
                    }
                    , 'get');

            Route::add('/admin/usuarios/add',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->procesarAdd();
                    }
                    , 'post');

            Route::add('/admin/usuarios/view/([0-9]+)',
                    function ($idUsuario) {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->verUsuario($idUsuario);
                    }
                    , 'get');

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

            Route::add('/admin/usuarios/delete/([0-9]+)',
                    function ($idUsuario) {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->procesarDelete($idUsuario);
                    }
                    , 'get');

            Route::add('/admin/tareas',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\TareaController();
                        $controlador->mostrarTareas();
                    }
                    , 'get');

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

            Route::add('/admin/tareas/delete/([0-9]+)',
                    function ($idTarea) {
                        $controlador = new \Com\Daw2\Controllers\TareaController();
                        $controlador->procesarDelete($idTarea);
                    }
                    , 'get');

            Route::add('/admin/proyectos',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\ProyectoController();
                        $controlador->mostrarProyectos();
                    }
                    , 'get');

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

            Route::add('/admin/proyectos/delete/([0-9]+)',
                    function ($idProyecto) {
                        $controlador = new \Com\Daw2\Controllers\ProyectoController();
                        $controlador->procesarDelete($idProyecto);
                    }
                    , 'get');
        } else {
            Route::add('/admin',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->login();
                    }
                    , 'get');

            Route::add('/admin',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->procesarLogin();
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
