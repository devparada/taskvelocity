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

    public function procesarDelete(int $idTarea) {
        $data = [];

        $modeloTarea = new \Com\Daw2\Models\TareaModel();
        if ($modeloTarea->deleteTarea($idTarea)) {
            $data["informacion"]["estado"] = "success";
            $data["informacion"]["texto"] = "La tarea con el id " . $idTarea . " ha sido eliminado correctamente";
        } else {
            $data["informacion"]["estado"] = "danger";
            $data["informacion"]["texto"] = "La tarea con el id " . $idTarea . " no ha sido eliminado correctamente";
        }

        $data = [];
        $data['titulo'] = 'Todas las tareas';
        $data['seccion'] = '/tareas';

        $data['tareas'] = $modeloTarea->mostrarTareas();

        $this->view->showViews(array('templates/header.view.php', 'tareas.view.php', 'templates/footer.view.php'), $data);
    }
}
