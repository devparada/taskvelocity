<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

class ProyectoController extends \Com\Daw2\Core\BaseController {

    private const MB = 1048576;

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

        $this->view->show('public/proyectos.view.php', $data);
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
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $data['seccion'] = '/admin/proyectos/add';
            $data['tituloDiv'] = 'Añadir proyecto';
        } else {
            $data['seccion'] = '/proyectos/crear';
        }

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.proyecto.view.php', 'admin/templates/footer.view.php'), $data);
        } else {
            $this->view->show('public/crear.proyecto.view.php', $data);
        }
    }

    public function procesarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir proyectos';
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $data['seccion'] = '/admin/proyectos/add';
            $data['tituloDiv'] = 'Añadir proyecto';
        } else {
            $data['seccion'] = '/proyectos/crear';
        }

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

        unset($_POST["enviar"]);

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $data["datos"] = $datos;

        $errores = $this->comprobarAdd($datos);

        if (empty($errores)) {
            $modeloProyecto = new \Com\Daw2\Models\ProyectoModel();

            if ($modeloProyecto->addProyecto($datos["nombre_proyecto"], $datos["descripcion_proyecto"], $datos["fecha_limite_proyecto"], $datos["id_usuarios_asociados"])) {
                if ($_SESSION["usuario"]["id_rol"] == 1) {
                    header("location: /admin/proyectos");
                } else {
                    header("location: /proyectos");
                }
            }
        } else {
            $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
            $data["usuarios"] = $modeloUsuario->mostrarUsuarios();

            $data["errores"] = $errores;

            if ($_SESSION["usuario"]["id_rol"] == 1) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.proyecto.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->show('public/crear.proyecto.view.php', $data);
            }
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

    private function comprobarAdd(array $data): array {
        $errores = [];

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();

        if (empty($data["nombre_proyecto"])) {
            $errores["nombre_proyecto"] = "El nombre del proyecto no debe estar vacío";
        }

        if (!empty($_FILES["proyecto"]["name"])) {
            if ($_FILES["proyecto"]["type"] != "image/jpeg" && $_FILES["proyecto"]["type"] != "image/png") {
                $errores["imagen_proyecto"] = "Tipo de imagen no aceptado";
            } else if (getimagesize($_FILES["proyecto"]["tmp_name"])[0] > 2048 || getimagesize($_FILES["proyecto"]["tmp_name"])[1] > 1024) {
                $errores["imagen_proyecto"] = "Dimensiones de imagen no válidas. Las dimensiones máximas son 256 x 256";
            } else if ($_FILES["proyecto"]["size"] > 10 * self::MB) {
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
