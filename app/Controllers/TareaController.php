<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Controllers;

class TareaController extends \Com\TaskVelocity\Core\BaseController {

    private const MB = 1048576;

    public function mostrarTareas(): void {
        $data = [];
        $data['titulo'] = 'Todas las tareas';
        $data['seccion'] = '/admin/tareas';

        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
        $data['tareas'] = $modeloTarea->mostrarTareas();

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $data['proyectos'] = $modeloProyecto->mostrarProyectos();

        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $this->view->showViews(array('admin/templates/header.view.php', 'admin/tarea.view.php', 'admin/templates/footer.view.php'), $data);
        } else {
            $this->view->show('public/tareas.view.php', $data);
        }
    }

    public function mostrarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir tareas';
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $data['seccion'] = '/admin/tareas/add';
            $data['tituloDiv'] = 'Añadir tarea';
        } else {
            $data['seccion'] = '/tareas/crear';
        }

        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $data["proyectos"] = $modeloProyecto->mostrarProyectos();

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.tarea.view.php', 'admin/templates/footer.view.php'), $data);
        } else {
            $this->view->show('public/crear.tarea.view.php', $data);
        }
    }

    public function procesarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir tareas';
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $data['seccion'] = '/admin/tareas/add';
            $data['tituloDiv'] = 'Añadir tarea';
        } else {
            $data['seccion'] = '/tareas/crear';
        }

        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $data["proyectos"] = $modeloProyecto->mostrarProyectos();

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

        unset($_POST["enviar"]);

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        // Si id_color_tarea está vacio se añade el 1 que es el color por defecto
        if ($datos["id_color_tarea"] == "") {
            $datos["id_color_tarea"] = "1";
        }

        if (empty($datos["fecha_limite_tarea"])) {
            $datos["fecha_limite_tarea"] = null;
        }

        if (!array_key_exists("id_usuarios_asociados", $datos)) {
            $datos["id_usuarios_asociados"] = [];
        }

        if (!array_key_exists("descripcion_tarea", $datos)) {
            $datos["descripcion_tarea"] = null;
        }

        $data["datos"] = $datos;

        $errores = $this->comprobarAddTareas($datos);

        if (empty($errores)) {
            $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();

            if ($modeloTarea->addTarea($datos["nombre_tarea"], $datos["fecha_limite_tarea"], $datos["id_color_tarea"], $datos["id_proyecto_asociado"], $datos["id_usuarios_asociados"], $datos["descripcion_tarea"])) {
                if ($_SESSION["usuario"]["id_rol"] == 1) {
                    header("location: /admin/tareas");
                } else {
                    header("location: /tareas");
                }
            }
        } else {
            $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
            $data["proyectos"] = $modeloProyecto->mostrarProyectos();

            $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
            $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

            $data["errores"] = $errores;

            if ($_SESSION["usuario"]["id_rol"] == 1) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.tarea.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->show('public/crear.tarea.view.php', $data);
            }
        }
    }

    public function procesarDelete(int $idTarea) {
        $data = [];

        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
        if ($modeloTarea->deleteTarea($idTarea)) {
            $data["informacion"]["estado"] = "success";
            $data["informacion"]["texto"] = "La tarea con el id " . $idTarea . " ha sido eliminado correctamente";
        } else {
            $data["informacion"]["estado"] = "danger";
            $data["informacion"]["texto"] = "La tarea con el id " . $idTarea . " no ha sido eliminado correctamente";
        }

        $data['titulo'] = 'Todas las tareas';
        $data['seccion'] = '/admin/tareas';

        $data['tareas'] = $modeloTarea->mostrarTareas();

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/tarea.view.php', 'admin/templates/footer.view.php'), $data);
    }

    private function comprobarAddTareas(array $data): array {
        $errores = [];

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();

        if (empty($data["nombre_tarea"])) {
            $errores["nombre_tarea"] = "El nombre de la tarea no debe estar vacío";
        }

        if (!empty($_FILES["imagen_tarea"]["name"])) {
            if ($_FILES["imagen_tarea"]["type"] != "image/jpeg" && $_FILES["imagen_tarea"]["type"] != "image/png") {
                $errores["imagen_tarea"] = "Tipo de imagen no aceptado";
            } else if (getimagesize($_FILES["imagen_tarea"]["tmp_name"])[0] > 2048 || getimagesize($_FILES["imagen_tarea"]["tmp_name"])[1] > 1624) {
                $errores["imagen_tarea"] = "Dimensiones de imagen no válidas. Las dimensiones máximas son 2048 x 1624";
            } else if ($_FILES["imagen_tarea"]["size"] > 20 * self::MB) {
                $errores["imagen_tarea"] = "Imagen demasiada pesada";
            }
        }

        if (!empty($data["fecha_limite_tarea"] && !preg_match("/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/", $data["fecha_limite_tarea"]))) {
            $errores["fecha_limite_tarea"] = "La fecha de nacimiento no tiene un formato válido. Ejemplo: 2024-04-09";
        }

        if (!filter_var($data["id_color_tarea"], FILTER_VALIDATE_INT)) {
            $errores["id_color_tarea"] = "El color debe ser un número";
        } else if (!empty($data["id_color_tarea"]) && !$modeloColor->comprobarColor($data["id_color_tarea"])) {
            $errores["id_color_tarea"] = "Debes seleccionar un color válido";
        }

        if (empty($data["id_proyecto_asociado"])) {
            $errores["id_proyecto_asociado"] = "La tarea debe estar asociada a un proyecto";
        } else if (!filter_var($data["id_proyecto_asociado"], FILTER_VALIDATE_INT)) {
            $errores["id_proyecto_asociado"] = "El proyecto debe ser un número";
        } else if (is_null($modeloProyecto->buscarProyectoPorId((int) $data["id_proyecto_asociado"]))) {
            $errores["id_proyecto_asociado"] = "El proyecto debe ser válido";
        }

        if (array_key_exists("id_usuarios_asociados", $data) && $data["id_usuarios_asociados"] != null) {
            if (!$modeloUsuario->comprobarUsuariosNumero($data["id_usuarios_asociados"])) {
                $errores["id_usuarios_asociados"] = "Algún dato introducido no es un número";
            } if (!$modeloUsuario->comprobarUsuarios($data["id_usuarios_asociados"])) {
                $errores["id_usuarios_asociados"] = "Algún usuario asociado no existe";
            }
        }

        return $errores;
    }
}
