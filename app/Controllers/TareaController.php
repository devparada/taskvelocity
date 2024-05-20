<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Controllers;

class TareaController extends \Com\TaskVelocity\Core\BaseController {

    /**
     * Recoge el valor del id de rol de admin de UsuarioController
     */
    private const ROL_ADMIN_USUARIOS = \Com\TaskVelocity\Controllers\UsuarioController::ROL_ADMIN;

    public function mostrarTareas(): void {
        $data = [];
        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            $data['titulo'] = 'Todas las tareas';
            $data['seccion'] = '/admin/tareas';
        } else {
            $data['titulo'] = 'Tus tareas';
            $data['seccion'] = '/tareas';
        }

        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();

        if (array_key_exists("etiqueta", $_GET)) {
            $data['tareas'] = $modeloTarea->mostrarTareasPorEtiqueta($_GET["etiqueta"]);
        } else {
            $data['tareas'] = $modeloTarea->mostrarTareas();
        }

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $data['proyectos'] = $modeloProyecto->mostrarProyectos();

        $modeloEiqueta = new \Com\TaskVelocity\Models\EtiquetaModel();
        $data["etiquetas"] = $modeloEiqueta->mostrarEtiquetas();

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            $this->view->showViews(array('admin/templates/header.view.php', 'admin/tarea.view.php', 'admin/templates/footer.view.php'), $data);
        } else {
            $this->view->showViews(array('public/tareas.view.php', 'public/plantillas/footer.view.php'), $data);
        }
    }

    public function mostrarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir tareas';
        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
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
        $data["usuarios"] = $modeloUsuario->mostrarUsuariosFormulario();

        $modeloEtiqueta = new \Com\TaskVelocity\Models\EtiquetaModel();
        $data["etiquetas"] = $modeloEtiqueta->mostrarEtiquetas();

        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.tarea.view.php', 'admin/templates/footer.view.php'), $data);
        } else {
            $this->view->showViews(array('public/crear.tarea.view.php', 'public/plantillas/footer.view.php'), $data);
        }
    }

    public function procesarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir tareas';
        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
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
        $data["usuarios"] = $modeloUsuario->mostrarUsuariosFormulario();

        $modeloEtiqueta = new \Com\TaskVelocity\Models\EtiquetaModel();
        $data["etiquetas"] = $modeloEtiqueta->mostrarEtiquetas();

        unset($_POST["enviar"]);

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        // Si id_color_tarea está vacio se añade el color favorito del usuario por defecto
        if ($datos["id_color_tarea"] == "") {
            $datos["id_color_tarea"] = $_SESSION["usuario"]["id_color_favorito"];
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

            if ($modeloTarea->addTarea($datos["nombre_tarea"], $datos["fecha_limite_tarea"], $datos["id_color_tarea"], $datos["id_proyecto_asociado"], $datos["id_usuarios_asociados"], $datos["descripcion_tarea"], $datos["id_etiqueta"])) {
                if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                    header("location: /admin/tareas");
                } else {
                    header("location: /tareas");
                }
            }
        } else {
            $data["errores"] = $errores;

            if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.tarea.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->showViews(array('public/crear.tarea.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        }
    }

    /**
     * Comprueba si el usuario logeado es miembro de la tarea o es admin
     * @param array|null $miembros los miembros de la tarea
     * @return bool Retorna true si es miembro o es admin y false si no
     */
    private function comprobarUsuarioMiembros(?array $miembros): bool {
        if (!is_null($miembros)) {
            foreach ($miembros as $persona) {
                if ($persona == $_SESSION["usuario"]["username"] || $_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Muestra la vista crear tareas para editar una tarea
     * @param int $idTarea el id de la tarea
     * @return void
     */
    public function mostrarEdit(int $idTarea): void {
        $data = [];

        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
        $miembrosTarea = $modeloTarea->buscarTareaPorId($idTarea)["nombresUsuarios"];

        if ($this->comprobarUsuarioMiembros($miembrosTarea)) {

            $data["datos"] = $modeloTarea->buscarTareaPorId($idTarea);

            $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
            $data["proyectos"] = $modeloProyecto->mostrarProyectos();

            $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
            $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

            $modeloEtiqueta = new \Com\TaskVelocity\Models\EtiquetaModel();
            $data["etiquetas"] = $modeloEtiqueta->mostrarEtiquetas();

            if ($_SESSION["usuario"]["id_usuario"] == 1) {
                $data['titulo'] = 'Editar tarea con el id ' . $idTarea;
                $data['seccion'] = '/admin/tareas/edit/' . $idTarea;
                $data['tituloDiv'] = 'Editar tarea';

                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.tarea.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $data['seccion'] = '/tareas/editar/' . $idTarea;
                $data['titulo'] = 'Editar tarea';

                $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
                $data["usuarios"] = $modeloUsuario->mostrarUsuariosFormulario();

                $this->view->showViews(array('public/crear.tarea.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        } else {
            header("location: /tareas");
        }
    }

    /**
     * Procesa cuando se edita la tarea
     * @param int $idTarea el id de la tarea
     * @return void
     */
    public function procesarEdit(int $idTarea): void {
        $data = [];
        $data['titulo'] = 'Editar tarea con el id ' . $idTarea;
        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            $data['seccion'] = '/admin/tareas/edit';
            $data['tituloDiv'] = 'Editar tarea';
        } else {
            $data['seccion'] = '/tareas/editar';
        }

        unset($_POST["enviar"]);

        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
        $miembrosTarea = $modeloTarea->buscarTareaPorId($idTarea)["nombresUsuarios"];

        if ($this->comprobarUsuarioMiembros($miembrosTarea)) {

            $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // Si id_color_tarea está vacio se añade el color favorito del usuario por defecto
            if ($datos["id_color_tarea"] == "") {
                $datos["id_color_tarea"] = $_SESSION["usuario"]["id_color_favorito"];
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

            $data["modoEdit"] = true;

            $errores = $this->comprobarAddTareas($datos);

            if (empty($errores)) {
                if ($modeloTarea->editTarea($datos["nombre_tarea"], $datos["fecha_limite_tarea"], $datos["id_color_tarea"], $datos["id_proyecto_asociado"], $datos["id_usuarios_asociados"], $datos["descripcion_tarea"], $datos["id_etiqueta"], $idTarea)) {
                    if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                        header("location: /admin/tareas");
                    } else {
                        header("location: /tareas");
                    }
                }
            } else {
                $data["errores"] = $errores;
                $this->view->showViews(array('public/crear.tarea.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        } else {
            header("location: /tareas");
        }
    }

    /**
     * Borra una tarea y su imagen
     * @param int $idTarea el id de la tarea
     * @return void
     */
    public function procesarDelete(int $idTarea): void {
        $data = [];

        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
        $miembrosTarea = $modeloTarea->buscarTareaPorId($idTarea);

        if (!is_null($miembrosTarea) || $this->comprobarUsuarioMiembros($miembrosTarea["nombresUsuarios"])) {
            if ($modeloTarea->deleteTarea($idTarea)) {
                $data["informacion"]["estado"] = "success";
                $data["informacion"]["texto"] = "La tarea ha sido eliminada correctamente";
            } else {
                $data["informacion"]["estado"] = "danger";
                $data["informacion"]["texto"] = "La tarea con el id " . $idTarea . " no ha sido eliminada correctamente";
            }

            $data['titulo'] = 'Todas las tareas';
            $data['seccion'] = '/admin/tareas';

            $data['tareas'] = $modeloTarea->mostrarTareas();

            if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/tarea.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $modeloEiqueta = new \Com\TaskVelocity\Models\EtiquetaModel();
                $data["etiquetas"] = $modeloEiqueta->mostrarEtiquetas();

                $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
                $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

                $this->view->showViews(array('public/tareas.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        } else {
            header("location: /tareas");
        }
    }

    /**
     * Mustra la información de una tarea específicada a partir del id específicado
     * @param int $idTarea el id de la tarea
     * @return void
     * */
    public function verTarea(int $idTarea): void {
        $data = [];

        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $data["titulo"] = "Ver tarea $idTarea";
            $data["tituloDiv"] = "Ver tarea $idTarea";
            $data["seccion"] = "/admin/tareas/view/$idTarea";
        }

        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
        $miembrosTarea = $modeloTarea->buscarTareaPorId($idTarea)["nombresUsuarios"];

        $data["modoVer"] = true;

        if ($this->comprobarUsuarioMiembros($miembrosTarea)) {
            $data["datos"] = $modeloTarea->buscarTareaPorId($idTarea);
            $data["tarea"] = $data["datos"];
            $data["usuarios"] = $modeloTarea->mostrarUsuariosPorTarea($idTarea);

            $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
            $data["proyectos"] = $modeloProyecto->mostrarProyectos();

            $data["modoVer"] = true;
            $data["idTarea"] = $idTarea;

            if ($_SESSION["usuario"]["id_rol"] == 1) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.tarea.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->show('public/ver.tarea.view.php', $data);
            }
        } else {
            header("location: /tareas");
        }
    }

    private function comprobarAddTareas(array $data): array {
        $errores = [];

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $modeloEtiqueta = new \Com\TaskVelocity\Models\EtiquetaModel();

        if (empty($data["nombre_tarea"])) {
            $errores["nombre_tarea"] = "El nombre de la tarea no debe estar vacío";
        }

        if (empty($data["id_etiqueta"])) {
            $errores["id_etiqueta"] = "Tienes que seleccionar una etiqueta";
        } else if (!$modeloEtiqueta->comprobarEtiqueta($data["id_etiqueta"])) {
            $errores["id_etiqueta"] = "Tiene que ser una etiqueta válida";
        }

        if (!empty($_FILES["imagen_tarea"]["name"])) {
            if ($_FILES["imagen_tarea"]["type"] != "image/jpeg" && $_FILES["imagen_tarea"]["type"] != "image/png") {
                $errores["imagen_tarea"] = "Tipo de imagen no aceptado";
            } else if (getimagesize($_FILES["imagen_tarea"]["tmp_name"])[0] > 2048 || getimagesize($_FILES["imagen_tarea"]["tmp_name"])[1] > 1624) {
                $errores["imagen_tarea"] = "Dimensiones de imagen no válidas. Las dimensiones máximas son 2048 x 1624";
            } else if ($_FILES["imagen_tarea"]["size"] > 20 * \Com\TaskVelocity\Models\FileModel::MB) {
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

        if (!empty($data["descripcion_tarea"]) && strlen($data["descripcion_tarea"]) > 255) {
            $errores["descripcion_tarea"] = "La descripción es muy larga";
        }

        return $errores;
    }
}
