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

        Route::add('/usuarios/view/([0-9]+)',
                function ($idUsuario) {
                    $controlador = new \Com\Daw2\Controllers\UsuarioController();
                    $controlador->verUsuario($idUsuario);
                }
                , 'get');

        Route::add('/usuarios/edit/([0-9]+)',
                function ($idUsuario) {
                    $controlador = new \Com\Daw2\Controllers\UsuarioController();
                    $controlador->mostrarEdit($idUsuario);
                }
                , 'get');

        Route::add('/usuarios/edit/([0-9]+)',
                function ($idUsuario) {
                    $controlador = new \Com\Daw2\Controllers\UsuarioController();
                    $controlador->procesarEdit($idUsuario);
                }
                , 'post');

        Route::add('/usuarios/delete/([0-9]+)',
                function ($idUsuario) {
                    $controlador = new \Com\Daw2\Controllers\UsuarioController();
                    $controlador->procesarDelete($idUsuario);
                }
                , 'get');

        Route::add('/tareas',
                function () {
                    $controlador = new \Com\Daw2\Controllers\TareaController();
                    $controlador->mostrarTareas();
                }
                , 'get');

        Route::add('/tareas/add',
                function () {
                    $controlador = new \Com\Daw2\Controllers\TareaController();
                    $controlador->mostrarAdd();
                }
                , 'get');

        Route::add('/tareas/add',
                function () {
                    $controlador = new \Com\Daw2\Controllers\TareaController();
                    $controlador->procesarAdd();
                }
                , 'post');

        Route::add('/tareas/delete/([0-9]+)',
                function ($idTarea) {
                    $controlador = new \Com\Daw2\Controllers\TareaController();
                    $controlador->procesarDelete($idTarea);
                }
                , 'get');

        Route::add('/proyectos',
                function () {
                    $controlador = new \Com\Daw2\Controllers\ProyectoController();
                    $controlador->mostrarProyectos();
                }
                , 'get');

        Route::add('/proyectos/add',
                function () {
                    $controlador = new \Com\Daw2\Controllers\ProyectoController();
                    $controlador->mostrarAdd();
                }
                , 'get');

        Route::add('/proyectos/add',
                function () {
                    $controlador = new \Com\Daw2\Controllers\ProyectoController();
                    $controlador->procesarAdd();
                }
                , 'post');

        Route::add('/proyectos/delete/([0-9]+)',
                function ($idProyecto) {
                    $controlador = new \Com\Daw2\Controllers\ProyectoController();
                    $controlador->procesarDelete($idProyecto);
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
