<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

class ProyectoController extends \Com\Daw2\Core\BaseController {

    public function mostrarProyectos(): void {
        $data = [];
        $data['titulo'] = 'Todos los proyectos';
        $data['seccion'] = '/proyectos';

        $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();
        $data['proyectos'] = $modeloProyecto->mostrarProyectos();

        $this->view->showViews(array('templates/header.view.php', 'proyectos.view.php', 'templates/footer.view.php'), $data);
    }
}
