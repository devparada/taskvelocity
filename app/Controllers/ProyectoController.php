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

        $this->view->showViews(array('templates/header.view.php', 'proyecto.view.php', 'templates/footer.view.php'), $data);
    }

    public function mostrarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir proyectos';
        $data['seccion'] = '/proyectos/add';
        $data['tituloDiv'] = 'Añadir proyecto';

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

        $this->view->showViews(array('templates/header.view.php', 'add.proyecto.view.php', 'templates/footer.view.php'), $data);
    }

    public function procesarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir proyectos';
        $data['seccion'] = '/proyectos/add';
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
                header("location: /proyectos");
            }
        } else {

            $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
            $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

            $data["errores"] = $errores;

            $this->view->showViews(array('templates/header.view.php', 'add.proyecto.view.php', 'templates/footer.view.php'), $data);
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
        $data['seccion'] = '/proyectos';

        $data['proyectos'] = $modeloProyecto->mostrarProyectos();

        $this->view->showViews(array('templates/header.view.php', 'proyecto.view.php', 'templates/footer.view.php'), $data);
    }
}
