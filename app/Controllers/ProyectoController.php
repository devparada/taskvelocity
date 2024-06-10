<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Controllers;

class ProyectoController extends \Com\TaskVelocity\Core\BaseController {

    private const ROL_ADMIN_USUARIOS = \Com\TaskVelocity\Controllers\UsuarioController::ROL_ADMIN;

    /**
     * Muestra la base de la información de los proyectos
     * @return void
     */
    public function mostrarProyectos(): void {
        $data = $this->mostrarProyectosComun();
        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        if (empty($_GET["pagina"])) {
            $_GET["pagina"] = 0;
        }

        // Elimina el error al añadir una tarea a un proyecto
        $_SESSION["error_addTareasProyecto"] = "";

        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            $data['titulo'] = 'Todos los proyectos';
            $data['seccion'] = '/admin/proyectos';
            $data["paginaActual"] = $_GET["pagina"];
            $data["maxPagina"] = $modeloProyecto->obtenerPaginas();
            $data["proyectos"] = $modeloProyecto->mostrarProyectos((int) $_GET["pagina"]++);
            $data["contarProyectos"] = $modeloProyecto->contador();
            $data["usuarios"] = $modeloUsuario->mostrarUsuariosFiltrosProyectos();
        } else {
            $data["titulo"] = "Tus proyectos";
            $data["seccion"] = "/proyectos";
        }

        if (!empty($_GET["id_usuario"])) {
            $data["tareas"] = $modeloProyecto->filtrarPorPropietario($_GET["id_usuario"], $data["paginaActual"]);
        }

        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            $this->view->showViews(array('admin/templates/header.view.php', 'admin/proyecto.view.php', 'admin/templates/footer.view.php'), $data);
        } else {
            $this->view->showViews(array('public/proyectos.view.php', 'public/plantillas/footer.view.php'), $data);
        }
    }

    /**
     * Muestra los proyectos de forma async cada 20 segundos
     * @return void
     */
    public function mostrarProyectosAsync(): void {
        $data = $this->mostrarProyectosComun();
        $this->view->showViews(array('public/proyectos-ajax.view.php'), $data);
    }

    /**
     * Retorna los proyectos que se usa de forma común en mostrarProyectos() y mostrarProyectosAsync()
     * @return array Retorna variables que se pasan a las vistas y son comunes
     */
    public function mostrarProyectosComun(): array {
        $data = [];

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $data["proyectos"] = $modeloProyecto->mostrarProyectos();

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

        return $data;
    }

    /**
     * Muestra la información de un proyecto pasado como parámetro
     * @param int $idProyecto el id del proyecto
     * @return void
     */
    public function verProyecto(int $idProyecto): void {
        $data = [];

        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            $data["titulo"] = "Ver proyecto $idProyecto";
            $data["tituloDiv"] = "Ver proyecto $idProyecto";
            $data["seccion"] = "/admin/proyectos/view/$idProyecto";
        } else {
            $data["seccion"] = "/proyectos/ver/$idProyecto";
            $data["titulo"] = "Ver proyecto";
        }

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();

        $miembrosProyecto = $modeloProyecto->buscarProyectoPorId($idProyecto)["nombresUsuarios"];
        $esPropietario = $modeloProyecto->esPropietario($idProyecto);
        if ($this->comprobarUsuarioMiembros($miembrosProyecto, $esPropietario)) {

            $data["datos"] = $modeloProyecto->buscarProyectoPorId($idProyecto);
            $data["proyecto"] = $data["datos"];

            $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
            $data["tareas"] = $modeloTarea->mostrarTareasPorProyecto($idProyecto);
            $data["todasTareas"] = $modeloTarea->mostrarTareasAddProyecto($idProyecto);

            $data["usuarios"] = $modeloProyecto->mostrarUsuariosPorProyecto($idProyecto);

            $data["modoVer"] = true;
            $data["idProyecto"] = $idProyecto;
            $_SESSION["historial"] = $_SERVER["REQUEST_URI"];

            if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
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
        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            $data['seccion'] = '/admin/proyectos/add';
            $data['tituloDiv'] = 'Añadir proyecto';
        } else {
            $data['seccion'] = '/proyectos/crear';
        }

        $data["enviar"] = "Crear proyecto";

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuariosFormulario();

        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
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
        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
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

        $errores = $this->comprobarComun($datos);

        if (empty($datos["id_usuarios_asociados"])) {
            $datos["id_usuarios_asociados"] = null;
        }

        if (empty($datos["fecha_limite_proyecto"])) {
            $datos["fecha_limite_proyecto"] = null;
        }

        if (empty($errores)) {
            $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();

            if ($modeloProyecto->addProyecto($datos["nombre_proyecto"], $datos["descripcion_proyecto"], $datos["fecha_limite_proyecto"], $datos["id_usuarios_asociados"])) {
                if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                    header("location: /admin/proyectos");
                } else {
                    header("location: /proyectos");
                }
            }
        } else {
            $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
            $data["usuarios"] = $modeloUsuario->mostrarUsuariosFormulario();

            $data["errores"] = $errores;
            $data["enviar"] = "Crear proyecto";

            if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.proyecto.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->showViews(array('public/crear.proyecto.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        }
    }

    /**
     * Comprueba si el usuario logeado es miembro del proyecto o es admin
     * @param array|null $miembros los miembros del proyecto
     * @param bool $esPropietario si el miembro es propietario del proyecto
     * @return bool Retorna true si es miembro o es admin y false si no
     */
    private function comprobarUsuarioMiembros(?array $miembros, bool $esPropietario): bool {
        if (!is_null($miembros)) {
            foreach ($miembros as $persona) {
                if ($persona == $_SESSION["usuario"]["username"] || $_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS || $esPropietario) {
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
        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();

        $miembrosProyecto = $modeloProyecto->buscarProyectoPorId($idProyecto)["nombresUsuarios"];
        $esPropietario = $modeloProyecto->esPropietario($idProyecto);
        if ($this->comprobarUsuarioMiembros($miembrosProyecto, $esPropietario)) {
            $data = [
                "enviar" => "Guardar cambios",
                "datos" => $modeloProyecto->buscarProyectoPorId($idProyecto),
                "usuarios" => json_encode($modeloProyecto->procesarUsuariosPorProyecto($idProyecto), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                "idProyecto" => $idProyecto,
                "modoEdit" => true
            ];

            if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                $data['tituloDiv'] = 'Editar proyecto con el id ' . $idProyecto;
                $data['seccion'] = '/admin/proyectos/edit/' . $idProyecto;
                $data['titulo'] = 'Editar proyecto ' . $idProyecto;

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
        unset($_POST["enviar"]);

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();

        $miembrosProyecto = $modeloProyecto->buscarProyectoPorId($idProyecto)["nombresUsuarios"];
        $esPropietario = $modeloProyecto->esPropietario($idProyecto);
        if ($this->comprobarUsuarioMiembros($miembrosProyecto, $esPropietario)) {

            $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                "datos" => $datos,
                "usuarios" => json_encode($modeloProyecto->procesarUsuariosPorProyecto($idProyecto), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                "modoEdit" => true
            ];

            if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                $data['seccion'] = '/admin/proyectos/edit/' . $idProyecto;
                $data['tituloDiv'] = 'Editar proyecto ' . $idProyecto;
                $data['titulo'] = 'Editar proyecto con el id ' . $idProyecto;
            } else {
                $data['seccion'] = '/proyectos/editar/' . $idProyecto;
                $data["titulo"] = "Editar proyecto";
            }

            $errores = $this->comprobarComun($datos);

            if (empty($datos["id_usuarios_asociados"])) {
                $datos["id_usuarios_asociados"] = null;
            }

            if (empty($datos["fecha_limite_proyecto"])) {
                $datos["fecha_limite_proyecto"] = null;
            }

            if (empty($errores)) {
                if ($modeloProyecto->editProyecto($datos["nombre_proyecto"], $datos["fecha_limite_proyecto"], $datos["id_usuarios_asociados"], $datos["descripcion_proyecto"], $idProyecto)) {
                    if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                        header("location: /admin/proyectos");
                    } else {
                        header("location: /proyectos");
                    }
                }
            } else {
                $data["errores"] = $errores;
                $data["idProyecto"] = $idProyecto;
                $data["enviar"] = "Guardar cambios";

                if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                    $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.proyecto.view.php', 'admin/templates/footer.view.php'), $data);
                } else {
                    $this->view->showViews(array('public/crear.proyecto.view.php', 'public/plantillas/footer.view.php'), $data);
                }
            }
        } else {
            header("location: /proyectos");
        }
    }

    public function procesarAddTareasProyecto(int $idProyecto): void {
        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();

        if (isset($_POST)) {
            $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        $_SESSION["error_addTareasProyecto"] = "";

        if (!empty($datos["id_tareas_asociadas"])) {
            $modeloTarea->addTareasProyecto($datos["id_tareas_asociadas"], (int) $idProyecto);
            header("location: /proyectos/ver/$idProyecto");
        } else {
            $_SESSION["error_addTareasProyecto"] = "Tienes que selecionar o crear una tarea";
            $this->verProyecto($idProyecto);
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
        $proyectoEncontrado = $modeloProyecto->buscarProyectoPorId($idProyecto);

        $miembrosProyecto = $modeloProyecto->buscarProyectoPorId($idProyecto)["nombresUsuarios"];
        $esPropietario = $modeloProyecto->esPropietario($idProyecto);
        if ($this->comprobarUsuarioMiembros($miembrosProyecto, $esPropietario)) {
            $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

            $data = [
                "titulo" => "Todos los proyectos",
                "seccion" => "/admin/proyectos",
                "usuarios" => $modeloUsuario->mostrarUsuarios(),
                "proyectos" => $modeloProyecto->mostrarProyectos()
            ];

            if ($modeloProyecto->deleteProyecto($idProyecto)) {
                $data["informacion"]["estado"] = "success";
                $data["informacion"]["texto"] = "El proyecto " . $proyectoEncontrado["nombre_proyecto"] . " ha sido eliminado correctamente";
            } else {
                $data["informacion"]["estado"] = "danger";
                $data["informacion"]["texto"] = "El proyecto " . $proyectoEncontrado["nombre_proyecto"] . " no ha sido eliminado correctamente";
            }

            if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                if (!array_key_exists("pagina", $_GET)) {
                    $_GET["pagina"] = 0;
                }

                $data["paginaActual"] = $_GET["pagina"];
                $data["maxPagina"] = $modeloProyecto->obtenerPaginas();
                $data["proyectos"] = $modeloProyecto->mostrarProyectos((int) $_GET["pagina"]++);
                $data["contarProyectos"] = $modeloProyecto->contador();
                $data["usuarios"] = $modeloUsuario->mostrarUsuariosFiltrosProyectos();

                $this->view->showViews(array('admin/templates/header.view.php', 'admin/proyecto.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->showViews(array('public/proyectos.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        } else {
            header("location: /proyectos");
        }
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
            if ($_FILES["imagen_proyecto"]["type"] == "image/gif") {
                $errores["imagen_proyecto"] = "Tipo de imagen no aceptado";
            } else if ($_FILES["imagen_proyecto"]["size"] > 10 * \Com\TaskVelocity\Models\FileModel::MB) {
                $errores["imagen_proyecto"] = "Imagen demasiada pesada";
            }
        }

        if (!empty($data["fecha_limite_proyecto"])) {
            if (!preg_match("/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/", $data["fecha_limite_proyecto"])) {
                $errores["fecha_limite_proyecto"] = "La fecha límite no tiene un formato válido. Ejemplo: 2024-04-29";
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

        if (!empty($data["descripcion_proyecto"]) && strlen($data["descripcion_proyecto"]) > 255) {
            $errores["descripcion_proyecto"] = "La descripción es muy larga";
        }

        return $errores;
    }
}
