<?php

namespace Com\TaskVelocity\Controllers;

class InicioController extends \Com\TaskVelocity\Core\BaseController {

    public function indexAdmin(): void {
        $data = array(
            'titulo' => 'Página de inicio',
            'seccion' => '/admin',
        );

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $data['numProyectos'] = $modeloProyecto->contador();

        $modeloTareas = new \Com\TaskVelocity\Models\TareaModel();
        $data['numTareas'] = $modeloTareas->contador();

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data['numUsuarios'] = $modeloUsuario->contador();

        $modeloLog = new \Com\TaskVelocity\Models\LogModel();
        $data["logs"] = $modeloLog->mostrarLogsInicio();

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/inicio.view.php', 'admin/templates/footer.view.php'), $data);
    }

    public function index(): void {
        $data = [];

        $this->view->show('public/inicio.view.php', $data);
    }

    /**
     * Cierra la sesión del usuario
     * @return void
     */
    public function logout(): void {
        session_destroy();
        header("location: /");
    }

    /**
     * Redirige a /login en los apartados restringidos
     * @return void
     */
    public function restingidoRedireccion(): void {
        header("location: /login");
    }
}
