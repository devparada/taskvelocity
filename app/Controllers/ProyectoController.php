<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Controllers;

class ProyectoController extends \Com\TaskVelocity\Core\BaseController {

    /**
     * El valor de 1MB en bytes
     */
    private const MB = 1048576;

    /**
     * Muestra la información de los proyectos
     * @return void
     */
    public function mostrarProyectos(): void {
        $data = [];
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $data['titulo'] = 'Todos los proyectos';
            $data['seccion'] = '/admin/proyectos';
        }

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $data['proyectos'] = $modeloProyecto->mostrarProyectos();

        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $this->view->showViews(array('admin/templates/header.view.php', 'admin/proyecto.view.php', 'admin/templates/footer.view.php'), $data);
        } else {
            $this->view->showViews(array('public/proyectos.view.php', 'public/plantillas/footer.view.php'), $data);
        }
    }

    /**
     * Muestra la información de un proyecto pasado como parámetro
     * @param int $idProyecto el id del proyecto
     * @return void
     */
    public function verProyecto(int $idProyecto): void {
        $data = [];

        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $data["titulo"] = "Ver proyecto $idProyecto";
            $data["tituloDiv"] = "Ver proyecto $idProyecto";
            $data["seccion"] = "/admin/proyectos/view/$idProyecto";
        } else {
            $data["seccion"] = "/proyectos/ver/$idProyecto";
        }

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $miembrosProyectos = $modeloProyecto->buscarProyectoPorId($idProyecto)["nombresUsuarios"];

        if ($this->comprobarUsuarioMiembros($miembrosProyectos)) {

            $data["datos"] = $modeloProyecto->buscarProyectoPorId($idProyecto);
            $data["proyecto"] = $data["datos"];

            $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
            $data["tareas"] = $modeloTarea->mostrarTareasPorProyecto($idProyecto);
            $data["todasTareas"] = $modeloTarea->mostrarTareas();

            $data["usuarios"] = $modeloProyecto->mostrarUsuariosPorProyecto($idProyecto);

            $data["modoVer"] = true;
            $data["idProyecto"] = $idProyecto;

            if ($_SESSION["usuario"]["id_rol"] == 1) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.proyecto.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->showViews(array('public/ver.proyecto.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        } else {
            header("location: /proyectos");
        }
    }

    /**
     * Muestra el formulario de añadir proyecto
     * @return void
     */
    public function mostrarAdd(): void {
        $data = [];
        $data['titulo'] = 'Añadir proyecto';
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $data['seccion'] = '/admin/proyectos/add';
            $data['tituloDiv'] = 'Añadir proyecto';
        } else {
            $data['seccion'] = '/proyectos/crear';
        }

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuariosFormulario();

        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.proyecto.view.php', 'admin/templates/footer.view.php'), $data);
        } else {
            $this->view->showViews(array('public/crear.proyecto.view.php', 'public/plantillas/footer.view.php'), $data);
        }
    }

    /**
     * Procesa cuando se añade un proyecto a la base de datos
     * @return void
     */
    public function procesarAdd(): void {
        $data = [];
        $data['titulo'] = 'Añadir proyecto';
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $data['seccion'] = '/admin/proyectos/add';
            $data['tituloDiv'] = 'Añadir proyecto';
        } else {
            $data['seccion'] = '/proyectos/crear';
        }

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuariosFormulario();

        unset($_POST["enviar"]);

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $data["datos"] = $datos;

        $errores = $this->comprobarAdd($datos);

        if (empty($datos["id_usuarios_asociados"])) {
            $datos["id_usuarios_asociados"] = null;
        }

        if (empty($datos["fecha_limite_proyecto"])) {
            $datos["fecha_limite_proyecto"] = null;
        }

        if (empty($errores)) {
            $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();

            if ($modeloProyecto->addProyecto($datos["nombre_proyecto"], $datos["descripcion_proyecto"], $datos["fecha_limite_proyecto"], $datos["id_usuarios_asociados"])) {
                if ($_SESSION["usuario"]["id_rol"] == 1) {
                    header("location: /admin/proyectos");
                } else {
                    header("location: /proyectos");
                }
            }
        } else {
            $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
            $data["usuarios"] = $modeloUsuario->mostrarUsuariosFormulario();

            $data["errores"] = $errores;

            if ($_SESSION["usuario"]["id_rol"] == 1) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.proyecto.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->showViews(array('public/crear.proyecto.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        }
    }

    /**
     * Comprueba si el usuario logeado es miembro del proyecto o es admin
     * @param array|null $miembros los miembros del proyecto
     * @return bool Retorna true si es miembro o es admin y false si no
     */
    private function comprobarUsuarioMiembros(?array $miembros): bool {
        if (!is_null($miembros)) {
            foreach ($miembros as $persona) {
                if ($persona == $_SESSION["usuario"]["username"] || $_SESSION["usuario"]["id_rol"] == 1) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Muestra el formualrio de editar proyecto
     * @param int $idProyecto el id del proyecto
     * @return void
     */
    public function mostrarEdit(int $idProyecto): void {
        $data = [];
        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();

        $miembrosProyectos = $modeloProyecto->buscarProyectoPorId($idProyecto)["nombresUsuarios"];
        if ($this->comprobarUsuarioMiembros($miembrosProyectos)) {

            $data["datos"] = $modeloProyecto->buscarProyectoPorId($idProyecto);

            $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
            $data["usuarios"] = $modeloUsuario->mostrarUsuariosFormulario();

            if ($_SESSION["usuario"]["id_usuario"] == 1) {
                $data['titulo'] = 'Editar proyecto con el id ' . $idProyecto;
                $data['seccion'] = '/admin/proyectos/edit/' . $idProyecto;
                $data['tituloDiv'] = 'Editar proyecto';

                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.proyecto.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $data['seccion'] = '/proyectos/editar/' . $idProyecto;
                $data['titulo'] = 'Editar proyecto';

                $this->view->showViews(array('public/crear.proyecto.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        } else {
            header("location: /proyectos");
        }
    }

    /**
     * Procesa cuando se edita un proyecto
     * @param int $idProyecto el id del proyecto
     * @return void
     */
    public function procesarEdit(int $idProyecto): void {
        $data = [];
        $data['titulo'] = 'Editar proyecto con el id ' . $idProyecto;
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $data['seccion'] = '/admin/proyectos/edit';
            $data['tituloDiv'] = 'Editar proyecto';
        } else {
            $data['seccion'] = '/proyectos/editar';
        }

        unset($_POST["enviar"]);

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();

        $miembrosProyecto = $modeloProyecto->buscarProyectoPorId($idProyecto)["nombresUsuarios"];
        if ($this->comprobarUsuarioMiembros($miembrosProyecto)) {

            $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data["datos"] = $datos;

            $data["modoEdit"] = true;

            $errores = $this->comprobarEdit($datos);

            if (empty($datos["id_usuarios_asociados"])) {
                $datos["id_usuarios_asociados"] = null;
            }

            if (empty($datos["fecha_limite_proyecto"])) {
                $datos["fecha_limite_proyecto"] = null;
            }

            if (empty($errores)) {
                if ($modeloProyecto->editProyecto($datos["nombre_proyecto"], $datos["fecha_limite_proyecto"], $datos["id_usuarios_asociados"], $datos["descripcion_proyecto"], $idProyecto)) {
                    if ($_SESSION["usuario"]["id_rol"] == 1) {
                        header("location: /admin/proyectos");
                    } else {
                        header("location: /proyectos");
                    }
                }
            } else {
                $data["errores"] = $errores;

                if ($_SESSION["usuario"]["id_rol"] == 1) {
                    $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.proyecto.view.php', 'admin/templates/footer.view.php'), $data);
                } else {
                    $this->view->showViews(array('public/crear.proyecto.view.php', 'public/plantillas/footer.view.php'), $data);
                }
            }
        } else {
            header("location: /proyectos");
        }
    }

    public function mostrarAddTareasProyecto(int $idProyecto): void {
        $data = [];

        $data["titulo"] = "Añadir tareas al proyecto $idProyecto";
        $data["seccion"] = "/proyectos/addTareasProyecto/$idProyecto";

        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
        $data["tareas"] = $modeloTarea->mostrarTareas();
        $data["idProyecto"] = $idProyecto;

        $this->view->showViews(array('public/add.tareas.proyecto.view.php', 'public/plantillas/footer.view.php'), $data);
    }

    public function procesarAddTareasProyecto(int $idProyecto): void {
        $data["titulo"] = "Añadir tareas al proyecto $idProyecto";
        $data["seccion"] = "/proyectos/addTareasProyecto/$idProyecto";

        $data["idProyecto"] = $idProyecto;

        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        if ($modeloTarea->addTareasProyecto($datos["id_tareas_asociadas"], $idProyecto)) {
            header("location: /proyectos/ver/$idProyecto");
        } else {
            $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
            $data["tareas"] = $modeloTarea->mostrarTareas();
            $data["idProyecto"] = $idProyecto;

            $this->view->showViews(array('public/add.tareas.proyecto.view.php', 'public/plantillas/footer.view.php'), $data);
        }
    }

    /**
     * Procesa al eliminar un proyecto
     * @param int $idProyecto el id del proyecto
     * @return void
     */
    public function procesarDelete(int $idProyecto): void {
        $data = [];

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();

        $miembrosProyecto = $modeloProyecto->buscarProyectoPorId($idProyecto)["nombresUsuarios"];

        if ($this->comprobarUsuarioMiembros($miembrosProyecto)) {
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

            if ($_SESSION["usuario"]["id_rol"] == 1) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/proyecto.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->showViews(array('public/proyectos.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        } else {
            header("location: /proyectos");
        }
    }

    /**
     * Comprueba si los datos están bien introducidos al añadir un proyecto
     * @param array $data los datos a comprobar
     * @return array el array de los errores si hay errores
     */
    private function comprobarAdd(array $data): array {
        $errores = $this->comprobarComun($data);
        return $errores;
    }

    /**
     * Comprueba si los datos están bien introducidos al editar el proyecto
     * @param array $data los datos a comprobar
     * @return array el array de los errores si hay errores
     */
    private function comprobarEdit(array $data): array {
        $errores = $this->comprobarComun($data);
        return $errores;
    }

    /**
     * Comprueba si los datos están bien introducidos al añadir y editar el proyecto
     * @param array $data los datos a comprobar
     * @return array el array de los errores si hay errores
     */
    private function comprobarComun(array $data): array {
        $errores = [];

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        if (empty($data["nombre_proyecto"])) {
            $errores["nombre_proyecto"] = "El nombre del proyecto no debe estar vacío";
        }

        if (!empty($_FILES["imagen_proyecto"]["name"])) {
            if ($_FILES["imagen_proyecto"]["type"] != "image/jpeg" && $_FILES["imagen_proyecto"]["type"] != "image/png") {
                $errores["imagen_proyecto"] = "Tipo de imagen no aceptado";
            } else if (getimagesize($_FILES["imagen_proyecto"]["tmp_name"])[0] > 2048 || getimagesize($_FILES["imagen_proyecto"]["tmp_name"])[1] > 1624) {
                $errores["imagen_proyecto"] = "Dimensiones de imagen no válidas. Las dimensiones máximas son 2048 x 1624";
            } else if ($_FILES["imagen_proyecto"]["size"] > 10 * self::MB) {
                $errores["imagen_proyecto"] = "Imagen demasiada pesada";
            }
        }

        if (!empty($data["fecha_limite_proyecto"])) {
            if (!preg_match("/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/", $data["fecha_limite_proyecto"])) {
                $errores["fecha_limite_proyecto"] = "La fecha de nacimiento no tiene un formato válido. Ejemplo: 2024-04-29";
            }
        }

        if (!empty($data["usuarios_asociados"])) {
            if (!$modeloUsuario->comprobarUsuariosNumero($data["usuarios_asociados"])) {
                $errores["usuarios_asociados"] = "Algún usuario no es válido";
            }
            if (!$modeloUsuario->comprobarUsuarios($data["usuarios_asociados"])) {
                $errores["usuarios_asociados"] = "Algún usuario no existe";
            }
        }

        return $errores;
    }
}
