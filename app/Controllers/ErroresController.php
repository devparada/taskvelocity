<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

class ErroresController extends \Com\Daw2\Core\BaseController {

    function error404(): void {
        http_response_code(404);
        $data = ['titulo' => 'Error 404'];
        $data['texto'] = 'Error 404. URL no encontrada.';
        $this->view->show('admin/error.php', $data);
    }

    function error405(): void {
        http_response_code(405);
        $data = ['titulo' => 'Error 405'];
        $data['texto'] = 'Error 405. Método no disponible.';

        $this->view->show('admin/error.php', $data);
    }
}
