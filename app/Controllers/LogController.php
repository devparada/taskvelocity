<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Controllers;

class LogController extends \Com\TaskVelocity\Core\BaseController {

    /**
     * Muestra los logs de la base de datos
     * @return void
     */
    public function mostrarLogs(): void {
        $data = [];
        $data['titulo'] = 'Todos los logs';
        $data['seccion'] = '/admin/logs?pagina=1';

        $modeloLog = new \Com\TaskVelocity\Models\LogModel();
        $data['logs'] = $modeloLog->consultarPagina((int) $_GET["pagina"]++);
        $data["paginaActual"] = $_GET["pagina"] - 1;

        $data["maxPagina"] = floor($modeloLog->obtenerPáginas());
        $data["contarLogs"] = count($modeloLog->mostrarLogs());
        
        $this->view->showViews(array('admin/templates/header.view.php', 'admin/log.view.php', 'admin/templates/footer.view.php'), $data);
    }
}
