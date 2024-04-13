<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

class TareaController extends \Com\Daw2\Core\BaseController {

    public function mostrarTareas(): void {
        $data = [];
        $data['titulo'] = 'Todas las tareas';
        $data['seccion'] = '/tareas';

        $modeloTarea = new \Com\Daw2\Models\TareaModel();
        $data['tareas'] = $modeloTarea->mostrarTareas();

        $this->view->showViews(array('templates/header.view.php', 'tareas.view.php', 'templates/footer.view.php'), $data);
    }
}
