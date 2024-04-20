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

        $this->view->showViews(array('templates/header.view.php', 'tarea.view.php', 'templates/footer.view.php'), $data);
    }

    public function mostrarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir tareas';
        $data['seccion'] = '/tareas/add';
        $data['tituloDiv'] = 'Añadir tarea';

        $modeloColor = new \Com\Daw2\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();
        $data["proyectos"] = $modeloProyecto->mostrarProyectos();

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

        $this->view->showViews(array('templates/header.view.php', 'add.tarea.view.php', 'templates/footer.view.php'), $data);
    }

    public function procesarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir tareas';
        $data['seccion'] = '/tareas/add';
        $data['tituloDiv'] = 'Añadir tarea';

        $modeloColor = new \Com\Daw2\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();
        $data["proyectos"] = $modeloProyecto->mostrarProyectos();

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

        unset($_POST["enviar"]);

        // Si id_color_tarea está vacio se añade el 1 que es el color por defecto
        if ($_POST["id_color_tarea"] == "") {
            $_POST["id_color_tarea"] = "1";
        }

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $data["datos"] = $datos;

        $errores = [];

        if (empty($errores)) {
            $modeloTarea = new \Com\Daw2\Models\TareaModel();

            if ($modeloTarea->addTarea($datos["nombre_tarea"], $datos["fecha_limite_tarea"], $datos["id_color_tarea"], $datos["id_proyecto_asociado"], $datos["id_usuarios_asociados"], $datos["descripcion_tarea"])) {
                header("location: /tareas");
            }
        } else {
            $modeloColor = new \Com\Daw2\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            $data["errores"] = $errores;

            $this->view->showViews(array('templates/header.view.php', 'add.tarea.view.php', 'templates/footer.view.php'), $data);
        }
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

        $data['titulo'] = 'Todas las tareas';
        $data['seccion'] = '/tareas';

        $data['tareas'] = $modeloTarea->mostrarTareas();

        $this->view->showViews(array('templates/header.view.php', 'tarea.view.php', 'templates/footer.view.php'), $data);
    }
}
