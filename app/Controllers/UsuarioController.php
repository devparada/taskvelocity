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

        $errores = $this->comprobarAdd($datos);

        if (empty($errores)) {
            $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
            if ($modeloUsuario->addUsuario($datos["username"], $datos["contrasena"], $datos["email"], $datos["idRol"], $datos["fechaNac"], $datos["descripcion"], $datos["idColorFav"])) {
                $modeloUsuario->crearAvatar($datos["email"]);
                header("location: /usuarios");
            }
        } else {
            $modeloRol = new \Com\Daw2\Models\RolModel();
            $data["roles"] = $modeloRol->mostrarRoles();

            $modeloColor = new \Com\Daw2\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            $data["errores"] = $errores;

            $this->view->showViews(array('templates/header.view.php', 'add.usuario.view.php', 'templates/footer.view.php'), $data);
        }
    }

    public function procesarDelete(int $id) {
        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $modeloUsuario->deleteUsuario($id);
    }

    private function comprobarAdd(array $data): array {
        $errores = [];

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $modeloRol = new \Com\Daw2\Models\RolModel();
        $modeloColor = new \Com\Daw2\Models\ColorModel();

        define('MB', 1048576);

        if (empty($data["username"])) {
            $errores["username"] = "El nombre de usuario no debe estar vacío";
        } else if (!is_null($modeloUsuario->buscarUsuarioPorUsername($data["username"]))) {
            $errores["username"] = "El nombre de usuario ya existe";
        } else if (!preg_match("/^[a-z0-9]{4,}$/", $data["username"])) {
            $errores["username"] = "El nombre de usuario no cumple los mínimos. Mínimo 4 caracteres (letras y numeros)";
        }

        if (!empty($_FILES["avatar"]["name"])) {
            if ($_FILES["avatar"]["type"] != "image/jpeg" && $_FILES["avatar"]["type"] != "image/png") {
                $errores["avatar"] = "Tipo de imagen no aceptado";
            } else if (getimagesize($_FILES["avatar"]["tmp_name"])[0] > 256 || getimagesize($_FILES["avatar"]["tmp_name"])[1] > 256) {
                $errores["avatar"] = "Dimensiones de imagen no válidas. Las dimensiones máximas son 256 x 256";
            } else if ($_FILES["avatar"]["size"] > 20 * MB) {
                $errores["avatar"] = "Imagen demasiada pesada";
            }
        }

        if (empty($data["idRol"])) {
            $errores["idRol"] = "Debes seleccionar un rol";
        } else if (!filter_var($data["idRol"], FILTER_VALIDATE_INT)) {
            $errores["idRol"] = "El rol debe ser un número";
        } else if (!$modeloRol->comprobarRol($data["idRol"])) {
            $errores["idRol"] = "Debes seleccionar un rol válido";
        }

        if (empty($data["email"])) {
            $errores["email"] = "El email no debe estar vacío";
        } else if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            $errores["email"] = "El email debe ser un email válido";
        } else if (!is_null($modeloUsuario->buscarUsuarioPorEmail($data["email"]))) {
            $errores["email"] = "El email ya existe";
        }

        if (empty($data["contrasena"])) {
            $errores["contrasena"] = "La contraseña no debe estar vacía";
        } else if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[A-Za-z0-9]{8,15}$/", $data["contrasena"])) {
            $errores["contrasena"] = "La contraseña no cumple los mínimos. Tiene que contener 1 letra mayúscula, 1 minúscula y 1 número. Mínimo 8 caracteres";
        }

        if (empty($data["confirmarContrasena"])) {
            $errores["confirmarContrasena"] = "La confirmación de la contraseña no debe estar vacía";
        }

        if (!empty($data["contrasena"]) && !empty($data["confirmarContrasena"])) {
            if ($data["contrasena"] != $data["confirmarContrasena"]) {
                $errores["confirmarContrasena"] = "La contraseña y la confirmación de la contraseña debe ser la misma";
            }
        }

        if (empty($data["fechaNac"])) {
            $errores["fechaNac"] = "La fecha de nacimiento no debe estar vacía";
        } else if (!preg_match("/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/", $data["fechaNac"])) {
            $errores["fechaNac"] = "La fecha de nacimiento no tiene un formato válido. Ejemplo: 2024-04-09";
        }

        if (!filter_var($data["idColorFav"], FILTER_VALIDATE_INT)) {
            $errores["idColorFav"] = "El color debe ser un número";
        } else if (!empty($data["idColorFav"]) && !$modeloColor->comprobarColor($data["idColorFav"])) {
            $errores["idColorFav"] = "Debes seleccionar un color válido";
        }

        return $errores;
    }
}
