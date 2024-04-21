<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

class TareaController extends \Com\Daw2\Core\BaseController {

    private const MB = 1048576;

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

        $errores = $this->comprobarAddTareas($datos);

        if (empty($errores)) {
            $modeloTarea = new \Com\Daw2\Models\TareaModel();

            if ($modeloTarea->addTarea($datos["nombre_tarea"], $datos["fecha_limite_tarea"], $datos["id_color_tarea"], $datos["id_proyecto_asociado"], $datos["id_usuarios_asociados"], $datos["descripcion_tarea"])) {
                header("location: /tareas");
            }
        } else {
            $modeloColor = new \Com\Daw2\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();
            $data["proyectos"] = $modeloProyecto->mostrarProyectos();

            $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
            $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

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

    private function comprobarAddTareas(array $data): array {
        $errores = [];

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $modeloColor = new \Com\Daw2\Models\ColorModel();
        $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();

        if (empty($data["nombre_tarea"])) {
            $errores["nombre_tarea"] = "El nombre de la tarea no debe estar vacío";
        }

        $dimensionesImagen = 1024;

        if (!empty($_FILES["imagen_tarea"]["name"])) {
            if ($_FILES["imagen_tarea"]["type"] != "image/jpeg" && $_FILES["imagen_tarea"]["type"] != "image/png") {
                $errores["imagen_tarea"] = "Tipo de imagen no aceptado";
            } else if (getimagesize($_FILES["imagen_tarea"]["tmp_name"])[0] > $dimensionesImagen || getimagesize($_FILES["imagen_tarea"]["tmp_name"])[1] > $dimensionesImagen) {
                $errores["imagen_tarea"] = "Dimensiones de imagen no válidas. Las dimensiones máximas son 256 x 256";
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

        if (!array_key_exists("id_usuarios_asociados", $data)) {
            $errores["id_usuarios_asociados"] = "Tiene que haber asignado un usuario a la tarea";
        } else if (!$modeloUsuario->comprobarUsuariosNumero($data["id_usuarios_asociados"])) {
            $errores["id_usuarios_asociados"] = "Algún dato introducido no es un número";
        } else if (!$modeloUsuario->comprobarUsuarios($data["id_usuarios_asociados"])) {
            $errores["id_usuarios_asociados"] = "Algún usuario asociado no existe";
        }

        return $errores;
    }
}
