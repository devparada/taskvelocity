<?php

namespace Com\Daw2\Core;

use Steampixel\Route;

class FrontController {

    static function main() {

        // Rutas que requieren login para acceder
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

            Route::add('/admin/usuarios/view/([A-Za-z0-9\-]+)',
                    function ($uuidUsuario) {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->verUsuario($uuidUsuario);
                    }
                    , 'get');

            Route::add('/admin/usuarios/edit/([A-Za-z0-9\-]+)',
                    function ($uuidUsuario) {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->mostrarEdit($uuidUsuario);
                    }
                    , 'get');

            Route::add('/admin/usuarios/edit/([A-Za-z0-9\-]+)',
                    function ($uuidUsuario) {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->procesarEdit($uuidUsuario);
                    }
                    , 'post');

            Route::add('/admin/usuarios/delete/([A-Za-z0-9\-]+)',
                    function ($uuidUsuario) {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->procesarDelete($uuidUsuario);
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

            Route::add('/admin/tareas/delete/([A-Za-z0-9\-]+)',
                    function ($uuidTarea) {
                        $controlador = new \Com\Daw2\Controllers\TareaController();
                        $controlador->procesarDelete($uuidTarea);
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

            Route::add('/admin/proyectos/delete/([A-Za-z0-9\-]+)',
                    function ($uuidProyecto) {
                        $controlador = new \Com\Daw2\Controllers\ProyectoController();
                        $controlador->procesarDelete($uuidProyecto);
                    }
                    , 'get');

            Route::add('/proyectos',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\ProyectoController();
                        $controlador->mostrarProyectosPublic();
                    }
                    , 'get');

            Route::add('/proyectos/ver/([A-Za-z0-9\-]+)',
                    function ($uuidProyecto) {
                        $controlador = new \Com\Daw2\Controllers\ProyectoController();
                        $controlador->verProyectoPublic($uuidProyecto);
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
                    , 'get');
            // Rutas que los usuarios pueden acceder sin logearse
        } else {
            Route::add('/login',
                    function () {
                        $controlador = new \Com\Daw2\Controllers\UsuarioController();
                        $controlador->login();
                    }
                    , 'get');

            Route::add('/login',
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
