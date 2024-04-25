<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

class ProyectoController extends \Com\Daw2\Core\BaseController {

    public function mostrarProyectos(): void {
        $data = [];
        $data['titulo'] = 'Todos los proyectos';
        $data['seccion'] = '/admin/proyectos';

        $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();
        $data['proyectos'] = $modeloProyecto->mostrarProyectos();

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/proyecto.view.php', 'admin/templates/footer.view.php'), $data);
    }

    public function mostrarProyectosPublic(): void {
        $data = [];

        $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();
        $data['proyectos'] = $modeloProyecto->mostrarProyectos();

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data['usuario'] = $modeloUsuario->buscarUsuarioPorId($_SESSION["usuario"]["id_usuario"]);

        $this->view->show('public/proyecto.view.php', $data);
    }

    public function verProyectoPublic(int $idProyecto): void {
        $data = [];

        $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();
        $data["proyecto"] = $modeloProyecto->buscarProyectoPorId($idProyecto);

        $modeloTarea = new \Com\Daw2\Models\TareaModel();
        $data["tareas"] = $modeloTarea->mostrarTareasPorProyecto($idProyecto);
        $data["miembros"] = $modeloTarea->mostrarUsuariosPorProyecto($idProyecto);

        $this->view->show('public/ver.proyecto.view.php', $data);
    }

    public function mostrarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir proyectos';
        $data['seccion'] = '/admin/proyectos/add';
        $data['tituloDiv'] = 'Añadir proyecto';

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.proyecto.view.php', 'admin/templates/footer.view.php'), $data);
    }

    public function procesarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir proyectos';
        $data['seccion'] = '/admin/proyectos/add';
        $data['tituloDiv'] = 'Añadir proyecto';

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

        unset($_POST["enviar"]);

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $data["datos"] = $datos;

        $errores = [];

        if (empty($errores)) {
            $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();

            if ($modeloProyecto->addProyecto($datos["nombre_proyecto"], $datos["descripcion_proyecto"], $datos["fecha_limite_proyecto"], $datos["id_usuarios_asociados"])) {
                header("location: /admin/proyectos");
            }
        } else {

            $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
            $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

            $data["errores"] = $errores;

            $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.proyecto.view.php', 'admin/templates/footer.view.php'), $data);
        }
    }

    public function procesarDelete(int $idProyecto) {
        $data = [];

        $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();
        if ($modeloProyecto->deleteProyecto($idProyecto)) {
            $data["informacion"]["estado"] = "success";
            $data["informacion"]["texto"] = "El proyecto con el id " . $idProyecto . " ha sido eliminado correctamente";
        } else {
            $data["informacion"]["estado"] = "danger";
            $data["informacion"]["texto"] = "El proyecto con el id " . $idProyecto . " no ha sido eliminado correctamente";
        }

        $data['titulo'] = 'Todos los proyectos';
        $data['seccion'] = '/admin/proyectos';

        $data['proyectos'] = $modeloProyecto->mostrarProyectos();

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/proyecto.view.php', 'admin/templates/footer.view.php'), $data);
    }
}
