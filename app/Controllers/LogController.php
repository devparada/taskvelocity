<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Controllers;

class LogController extends \Com\TaskVelocity\Core\BaseController {

    /**
     * Muestra los logs de la base de datos
     * @return void
     */
    public function mostrarLogs(): void {
        $modeloLog = new \Com\TaskVelocity\Models\LogModel();
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        if (empty($_GET["pagina"])) {
            $_GET["pagina"] = 0;
        }

        $data = [
            "titulo" => "Todos los logs",
            "seccion" => "/admin/logs",
            "logs" => $modeloLog->consultarPagina((int) $_GET["pagina"]++),
            "paginaActual" => $_GET["pagina"] - 1,
            "maxPagina" => $modeloLog->obtenerPaginas(),
            "contarLogs" => count($modeloLog->mostrarLogs()),
            "usuarios" => $modeloUsuario->mostrarUsuariosFiltrosLogs()
        ];

        if (!empty($_GET["id_usuario"])) {
            $data["logs"] = $modeloLog->filtrarPorUsuario($_GET["id_usuario"], $data["paginaActual"]);
        }

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/log.view.php', 'admin/templates/footer.view.php'), $data);
    }
}
