<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

class UsuarioController extends \Com\Daw2\Core\BaseController {

    public function mostrarUsuarios() {
        $data = [];
        $data['titulo'] = 'Todos los usuarios';
        $data['seccion'] = '/usuarios';

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data['usuarios'] = $modeloUsuario->mostrarUsuarios();

        $this->view->showViews(array('templates/header.view.php', 'usuario.view.php', 'templates/footer.view.php'), $data);
    }

    public function mostrarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir usuarios';
        $data['seccion'] = '/usuarios/add';
        $data['tituloDiv'] = 'Añadir usuario';

        $modeloRol = new \Com\Daw2\Models\RolModel();
        $data["roles"] = $modeloRol->mostrarRoles();

        $modeloColor = new \Com\Daw2\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $this->view->showViews(array('templates/header.view.php', 'add.usuario.view.php', 'templates/footer.view.php'), $data);
    }

    public function procesarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir usuarios';
        $data['seccion'] = '/usuarios/add';
        $data['tituloDiv'] = 'Añadir usuario';

        unset($_POST["enviar"]);

        if ($_POST["idColorFav"] == "") {
            $_POST["idColorFav"] = "1";
        }

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $data["datos"] = $datos;

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        if ($modeloUsuario->addUsuario($datos["username"], $datos["contrasena"], $datos["email"], $datos["idRol"], $datos["fechaNac"], $datos["descripcion"], $datos["idColorFav"])) {
            header("location: /usuarios");
        } else {
            $modeloRol = new \Com\Daw2\Models\RolModel();
            $data["roles"] = $modeloRol->mostrarRoles();

            $modeloColor = new \Com\Daw2\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            $this->view->showViews(array('templates/header.view.php', 'add.usuario.view.php', 'templates/footer.view.php'), $data);
        }
    }
}
