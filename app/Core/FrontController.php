<?php

namespace Com\Daw2\Core;

use Steampixel\Route;

class FrontController {

    static function main() {

        Route::add('/',
                function () {
                    $controlador = new \Com\Daw2\Controllers\InicioController();
                    $controlador->index();
                }
                , 'get');

        Route::add('/usuarios',
                function () {
                    $controlador = new \Com\Daw2\Controllers\UsuarioController();
                    $controlador->mostrarUsuarios();
                }
                , 'get');

        Route::add('/usuarios/add',
                function () {
                    $controlador = new \Com\Daw2\Controllers\UsuarioController();
                    $controlador->mostrarAdd();
                }
                , 'get');

        Route::add('/usuarios/add',
                function () {
                    $controlador = new \Com\Daw2\Controllers\UsuarioController();
                    $controlador->procesarAdd();
                }
                , 'post');

        Route::add('/usuarios/delete/([0-9]+)',
                function ($id) {
                    $controlador = new \Com\Daw2\Controllers\UsuarioController();
                    $controlador->procesarDelete($id);
                }
                , 'get');

        Route::add('/tareas',
                function () {
                    $controlador = new \Com\Daw2\Controllers\TareaController();
                    $controlador->mostrarTareas();
                }
                , 'get');

        Route::add('/proyectos',
                function () {
                    $controlador = new \Com\Daw2\Controllers\ProyectoController();
                    $controlador->mostrarProyectos();
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
