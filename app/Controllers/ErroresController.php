<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Controllers;

class ErroresController extends \Com\TaskVelocity\Core\BaseController {

    function error404(): void {
        http_response_code(404);
        $data = [
            "titulo" => "Error 404",
            "texto" => "404. Estás en una URL que no existe"
        ];

        if (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["id_rol"] == \Com\TaskVelocity\Controllers\UsuarioController::ROL_ADMIN) {
            $this->view->showViews(array('admin/templates/header.view.php', 'admin/error.php', 'admin/templates/footer.view.php'), $data);
        } else {
            $this->view->showViews(array('public/error.php', 'public/plantillas/footer.view.php'), $data);
        }
    }

    function error405(): void {
        http_response_code(405);
        $data = [
            "titulo" => "Error 405",
            "texto" => "405. Método no disponible."
        ];

        if (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["id_rol"] == \Com\TaskVelocity\Controllers\UsuarioController::ROL_ADMIN) {
            $this->view->showViews(array('admin/templates/header.view.php', 'admin/error.php', 'admin/templates/footer.view.php'), $data);
        } else {
            $this->view->showViews(array('public/error.php', 'public/plantillas/footer.view.php'), $data);
        }
    }
}
