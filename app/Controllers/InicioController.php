<?php

namespace Com\TaskVelocity\Controllers;

class InicioController extends \Com\TaskVelocity\Core\BaseController {

    /**
     * Muestra el inicio de Administración
     * @return void
     */
    public function indexAdmin(): void {
        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $modeloTareas = new \Com\TaskVelocity\Models\TareaModel();
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $modeloLog = new \Com\TaskVelocity\Models\LogModel();

        $data = [
            "titulo" => "Inicio admin",
            "seccion" => "/admin",
            "numProyectos" => $modeloProyecto->contador(),
            "numTareas" => $modeloTareas->contador(),
            "numUsuarios" => $modeloUsuario->contador(),
            "logs" => $modeloLog->mostrarLogsInicio(),
        ];

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/inicio.view.php', 'admin/templates/footer.view.php'), $data);
    }

    /**
     * Muestra el inicio de los usuarios
     * @return void
     */
    public function index(): void {
        $data = [
            "titulo" => "Inicio"
        ];

        $this->view->showViews(array('public/inicio.view.php', 'public/plantillas/footer.view.php'), $data);
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
        $_SESSION["historial"] = $_SERVER["REQUEST_URI"];
        header("location: /login");
    }

    /**
     * Redirige a la última URL visitada por el usuario cuando está logeado
     * @return void
     */
    public function restingidoRedireccionUsuario(): void {
        header("location: /proyectos");
    }

    public function mostrarContacto(): void {
        $data = [
            "titulo" => "Contacto"
        ];

        $this->view->showViews(array('public/contacto.view.php', 'public/plantillas/footer.view.php'), $data);
    }
}
