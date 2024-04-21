<?php

namespace Com\Daw2\Controllers;

class InicioController extends \Com\Daw2\Core\BaseController {

    public function indexAdmin(): void {
        $data = array(
            'titulo' => 'Página de inicio',
            'breadcrumb' => ['Inicio']
        );
        $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();
        $data['numProyectos'] = $modeloProyecto->contador();

        $modeloTareas = new \Com\Daw2\Models\TareaModel();
        $data['numTareas'] = $modeloTareas->contador();

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data['numUsuarios'] = $modeloUsuario->contador();

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
}
