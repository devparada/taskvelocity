<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

class TareasController extends \Com\Daw2\Core\BaseController {

    public function mostrarTareas(): void {
        $data = [];
        $data['titulo'] = 'Todas las tareas';
        $data['seccion'] = '/tareas';

        $modeloUsuario = new \Com\Daw2\Models\TareasModel();
        $data['tareas'] = $modeloUsuario->mostrarTareas();

        $this->view->showViews(array('templates/header.view.php', 'tareas.view.php', 'templates/footer.view.php'), $data);
    }
}
