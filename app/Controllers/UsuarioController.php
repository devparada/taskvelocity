<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

class UsuarioController extends \Com\Daw2\Core\BaseController {

    private const MB = 1048576;

    public function mostrarUsuarios() {
        $data = [];
        $data['titulo'] = 'Todos los usuarios';
        $data['seccion'] = '/admin/usuarios';

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data['usuarios'] = $modeloUsuario->mostrarUsuarios();

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    public function login() {
        $data = [];

        $this->view->show('admin/login.view.php', $data);
    }

    public function procesarLogin() {
        $data = [];

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();

        if ($modeloUsuario->procesarLogin($datos["email"], $datos["password"]) && !empty($datos["email"]) && !empty($datos["password"])) {
            $usuarioEncontrado = $modeloUsuario->buscarUsuarioPorEmail($datos["email"]);

            $_SESSION["usuario"] = $usuarioEncontrado;
            $_SESSION["permisos"] = $this->verPermisos($usuarioEncontrado["id_rol"]);
            if ($usuarioEncontrado["id_rol"] == 1) {
                header("location: /admin");
            } else {
                header("location: /proyectos");
            }
        } else {
            $data["loginError"] = "Datos incorrectos";
            $this->view->show('admin/login.view.php', $data);
        }
    }

    public function mostrarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir usuarios';
        $data['seccion'] = '/admin/usuarios/add';
        $data['tituloDiv'] = 'Añadir usuario';

        $modeloRol = new \Com\Daw2\Models\RolModel();
        $data["roles"] = $modeloRol->mostrarRoles();

        $modeloColor = new \Com\Daw2\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    public function procesarAdd() {
        $data = [];
        $data['titulo'] = 'Añadir usuarios';
        $data['seccion'] = '/admin/usuarios/add';
        $data['tituloDiv'] = 'Añadir usuario';

        unset($_POST["enviar"]);

        // Si id_color está vacio se añade el 1 que es el color por defecto
        if ($_POST["id_color"] == "") {
            $_POST["id_color"] = "1";
        }

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $data["datos"] = $datos;

        $errores = $this->comprobarAdd($datos);

        if (empty($errores)) {
            $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
            if ($modeloUsuario->addUsuario($datos["username"], $datos["contrasena"], $datos["email"], $datos["id_rol"], $datos["fecha_nacimiento"], $datos["descripcion_usuario"], $datos["id_color"])) {
                $modeloUsuario->crearAvatar($datos["username"]);
                header("location: /usuarios");
            }
        } else {
            $modeloRol = new \Com\Daw2\Models\RolModel();
            $data["roles"] = $modeloRol->mostrarRoles();

            $modeloColor = new \Com\Daw2\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            $data["errores"] = $errores;

            $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
        }
    }

    public function mostrarEdit(int $idUsuario) {
        $data = [];
        $data['titulo'] = 'Editar usuario con el id ' . $idUsuario;
        $data['seccion'] = '/admin/usuarios/edit/' . $idUsuario;
        $data['tituloDiv'] = 'Editar usuario';

        $modeloRol = new \Com\Daw2\Models\RolModel();
        $data["roles"] = $modeloRol->mostrarRoles();

        $modeloColor = new \Com\Daw2\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data["datos"] = $modeloUsuario->buscarUsuarioPorId($idUsuario);

        $data["modoEdit"] = true;

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    public function procesarEdit(int $idUsuario) {
        $data = [];
        $data['titulo'] = 'Editar usuario con el id ' . $idUsuario;
        $data['seccion'] = '/admin/usuarios/edit/' . $idUsuario;
        $data['tituloDiv'] = 'Editar usuario';

        unset($_POST["enviar"]);

        // Si id_color está vacio se añade el 1 que es el color por defecto
        if ($_POST["id_color"] == "") {
            $_POST["id_color"] = "1";
        }

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data["datos"] = $datos;

        $data["modoEdit"] = true;

        $errores = $this->comprobarEdit($datos);

        if (empty($errores)) {
            $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();

            if ($modeloUsuario->editUsuario($datos["username"], $datos["contrasena"], $datos["email"], $datos["id_rol"], $datos["fecha_nacimiento"], $datos["descripcion_usuario"], $datos["id_color"], $idUsuario)) {
                if (!empty($_FILES["avatar"]["name"])) {
                    $modeloUsuario->updateAvatar($idUsuario);
                }
                header("location: /admin/usuarios");
            }
        } else {
            $modeloRol = new \Com\Daw2\Models\RolModel();
            $data["roles"] = $modeloRol->mostrarRoles();

            $modeloColor = new \Com\Daw2\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            $data["errores"] = $errores;

            $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
        }
    }

    public function verUsuario(int $idUsuario): void {
        $data = [];
        $data['titulo'] = 'Ver usuario con el id ' . $idUsuario;
        $data['seccion'] = '/admin/usuarios/view/' . $idUsuario;
        $data['tituloDiv'] = 'Ver usuario';

        $modeloRol = new \Com\Daw2\Models\RolModel();
        $data["roles"] = $modeloRol->mostrarRoles();

        $modeloColor = new \Com\Daw2\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();
        $data["datos"] = $modeloUsuario->buscarUsuarioPorId($idUsuario);
        $data["modoVer"] = true;

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    public function procesarDelete(int $idUsuario): void {
        $data = [];

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();

        if ($modeloUsuario->deleteUsuario($idUsuario)) {
            $data["informacion"]["estado"] = "success";
            $data["informacion"]["texto"] = "El usuario con el id " . $idUsuario . " ha sido eliminado correctamente";
        } else {
            $data["informacion"]["estado"] = "danger";
            $data["informacion"]["texto"] = "El usuario con el id " . $idUsuario . " no ha sido eliminado correctamente";
        }

        $data['titulo'] = 'Todos los usuarios';
        $data['seccion'] = '/admin/usuarios';

        $data['usuarios'] = $modeloUsuario->mostrarUsuarios();

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    private function comprobarComun(array $data): array {
        $errores = [];

        $modeloRol = new \Com\Daw2\Models\RolModel();
        $modeloColor = new \Com\Daw2\Models\ColorModel();

        $dimensionesAvatar = 256;

        if (!empty($_FILES["avatar"]["name"])) {
            if ($_FILES["avatar"]["type"] != "image/jpeg" && $_FILES["avatar"]["type"] != "image/png") {
                $errores["avatar"] = "Tipo de imagen no aceptado";
            } else if (getimagesize($_FILES["avatar"]["tmp_name"])[0] > $dimensionesAvatar || getimagesize($_FILES["avatar"]["tmp_name"])[1] > $dimensionesAvatar) {
                $errores["avatar"] = "Dimensiones de imagen no válidas. Las dimensiones máximas son 256 x 256";
            } else if ($_FILES["avatar"]["size"] > 10 * self::MB) {
                $errores["avatar"] = "Imagen demasiada pesada";
            }
        }

        if (empty($data["id_rol"])) {
            $errores["id_rol"] = "Debes seleccionar un rol";
        } else if (!filter_var($data["id_rol"], FILTER_VALIDATE_INT)) {
            $errores["id_rol"] = "El rol debe ser un número";
        } else if (!$modeloRol->comprobarRol($data["id_rol"])) {
            $errores["id_rol"] = "Debes seleccionar un rol válido";
        }

        if (empty($data["fecha_nacimiento"])) {
            $errores["fechaNac"] = "La fecha de nacimiento no debe estar vacía";
        } else if (!preg_match("/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/", $data["fecha_nacimiento"])) {
            $errores["fecha_nacimiento"] = "La fecha de nacimiento no tiene un formato válido. Ejemplo: 2024-04-09";
        }

        if (!filter_var($data["id_color"], FILTER_VALIDATE_INT)) {
            $errores["id_color"] = "El color debe ser un número";
        } else if (!empty($data["id_color"]) && !$modeloColor->comprobarColor($data["id_color"])) {
            $errores["id_color"] = "Debes seleccionar un color válido";
        }

        return $errores;
    }

    private function comprobarEdit(array $data): array {
        $errores = $this->comprobarComun($data);

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();

        if (!is_null($modeloUsuario->buscarUsuarioPorEmail($data["email"])) && $data["email"] != $modeloUsuario->buscarUsuarioPorEmail($data["email"])["email"]) {
            $errores["email"] = "El email ya existe";
        }

        if (!empty($data["contrasena"]) && !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[A-Za-z0-9]{8,15}$/", $data["contrasena"])) {
            $errores["contrasena"] = "La contraseña no cumple los mínimos. Tiene que contener 1 letra mayúscula, 1 minúscula y 1 número. Mínimo 8 caracteres";
        }

        if (!empty($data["contrasena"]) && empty($data["confirmarContrasena"])) {
            $errores["confirmarContrasena"] = "La confirmación de la contraseña no debe estar vacía";
        }

        if (!empty($data["contrasena"]) && !empty($data["confirmarContrasena"])) {
            if ($data["contrasena"] != $data["confirmarContrasena"]) {
                $errores["confirmarContrasena"] = "La contraseña y la confirmación de la contraseña debe ser la misma";
            }
        }

        return $errores;
    }

    private function comprobarAdd(array $data): array {
        $errores = $this->comprobarComun($data);

        $modeloUsuario = new \Com\Daw2\Models\UsuarioModel();

        if (empty($data["username"])) {
            $errores["username"] = "El nombre de usuario no debe estar vacío";
        } else if (!is_null($modeloUsuario->buscarUsuarioPorUsername($data["username"]))) {
            $errores["username"] = "El nombre de usuario ya existe";
        } else if (!preg_match("/^[a-z0-9]{4,}$/", $data["username"])) {
            $errores["username"] = "El nombre de usuario no cumple los mínimos. Mínimo 4 caracteres (letras y numeros)";
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

        return $errores;
    }

    private function verPermisos(int $idRol): array {
        $permisos = array(
            "inicio" => "",
            "usuarios" => "",
            "tareas" => "",
            "proyectos" => "",
        );

        switch ($idRol) {
            case 1:
                $permisos["inicio"] = "rwd";
                $permisos["usuarios"] = "rwd";
                $permisos["tareas"] = "rwd";
                $permisos["proyectos"] = "rwd";
                break;
            case 2:
                $permisos["inicio"] = "";
                $permisos["usuarios"] = "";
                $permisos["tareas"] = "";
                $permisos["proyectos"] = "";
                break;
        }

        return $permisos;
    }
}
