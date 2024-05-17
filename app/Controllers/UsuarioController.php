<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Controllers;

class UsuarioController extends \Com\TaskVelocity\Core\BaseController {

    private const MB = 1048576;

    public function mostrarUsuarios() {
        $data = [];
        $data['titulo'] = 'Todos los usuarios';
        $data['seccion'] = '/admin/usuarios';

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data['usuarios'] = $modeloUsuario->mostrarUsuarios();

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    public function mostrarLogin() {
        $data = [];

        $this->view->show('public/login.view.php', $data);
    }

    public function procesarLogin() {
        $data = [];

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        if ($modeloUsuario->procesarLogin($datos["email"], $datos["password"]) && !empty($datos["email"]) && !empty($datos["password"])) {
            $this->crearLogin($datos["email"]);
        } else {
            $data["loginError"] = "Datos incorrectos";
            $this->view->show('public/login.view.php', $data);
        }
    }

    private function crearLogin(string $email): void {
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $usuarioEncontrado = $modeloUsuario->buscarUsuarioPorEmail($email);

        $_SESSION["usuario"] = $usuarioEncontrado;
        $_SESSION["permisos"] = $this->verPermisos($usuarioEncontrado["id_rol"]);

        if ($usuarioEncontrado["id_rol"] == 1) {
            header("location: /admin");
        } else {
            header("location: /proyectos");
        }
    }

    public function mostrarRegister() {
        $data = [];

        $modeloColores = new \Com\TaskVelocity\Models\ColorModel();
        $data["colores"] = $modeloColores->mostrarColores();

        $this->view->show('public/register.view.php', $data);
    }

    public function mostrarAddUsuario() {
        $data = [];
        $data['titulo'] = 'Añadir usuario';
        $data['seccion'] = '/admin/usuarios/add';
        $data['tituloDiv'] = 'Añadir usuario';

        $modeloRol = new \Com\TaskVelocity\Models\RolModel();
        $data["roles"] = $modeloRol->mostrarRoles();

        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    /**
     * Este método procesa añadir un usuario a partir de los datos recibidos de un formulario
     * de añadir usuario (tiene en cuenta el id_rol) o register (no tiene en cuenta el id_rol) y
     * Cuando el usuario se registra, inicia sesión automáticamente.
     * @return void No devuelve nada
     */
    public function procesarAddUsuario(): void {
        $data = [];
        if (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["id_rol"] == 1) {
            $data['titulo'] = 'Añadir usuario';
            $data['seccion'] = '/admin/usuarios/add';
            $data['tituloDiv'] = 'Añadir usuario';
        }

        unset($_POST["enviar"]);

        // Si id_color está vacio se añade el 1 que es el color por defecto
        if ($_POST["id_color"] == "") {
            $_POST["id_color"] = "1";
        }

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $data["datos"] = $datos;

        $errores = $this->comprobarAdd($datos);

        if (empty($errores)) {
            $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
            if (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["id_rol"] = 1) {
                if ($modeloUsuario->addUsuario($datos["username"], $datos["contrasena"], $datos["email"], $datos["id_rol"], $datos["fecha_nacimiento"], $datos["descripcion_usuario"], $datos["id_color"])) {
                    header("location: /admin/usuarios");
                }
            } else {
                // El 2 es el id de rol del usuario (cuando se registra el usuario se añade el id de rol 2 que es usuario)
                if ($modeloUsuario->addUsuario($datos["username"], $datos["contrasena"], $datos["email"], 2, $datos["fecha_nacimiento"], "", $datos["id_color"])) {
                    $this->crearLogin($datos["email"]);
                    header("location: /proyectos");
                }
            }
        } else {
            $data["errores"] = $errores;

            $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            if (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["id_rol"] = 1) {
                $modeloRol = new \Com\TaskVelocity\Models\RolModel();
                $data["roles"] = $modeloRol->mostrarRoles();

                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->show('public/register.view.php', $data);
            }
        }
    }

    public function mostrarEdit(int $idUsuario) {
        $data = [];
        $data['titulo'] = 'Editar usuario con el id ' . $idUsuario;
        $data['seccion'] = '/admin/usuarios/edit/' . $idUsuario;
        $data['tituloDiv'] = 'Editar usuario';

        $modeloRol = new \Com\TaskVelocity\Models\RolModel();
        $data["roles"] = $modeloRol->mostrarRoles();

        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data["datos"] = $modeloUsuario->buscarUsuarioPorId($idUsuario);

        $data["modoEdit"] = true;

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    /**
     * Edita el usuario según los cambios que pase el usuario
     * @param int $idUsuario el id del usuario a editar
     * @return void
     */
    public function procesarEdit(int $idUsuario): void {
        $data = [];
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $data['titulo'] = 'Editar usuario con el id ' . $idUsuario;
            $data['seccion'] = '/admin/usuarios/edit/' . $idUsuario;
            $data['tituloDiv'] = 'Editar usuario';
        } else {
            $data['titulo'] = 'Tu perfil';
            $data['seccion'] = '/perfil/editar/' . $idUsuario;
            $data["tituloDiv"] = "Editando tus datos";
        }

        unset($_POST["enviar"]);

// Si id_color está vacio se añade el 1 que es el color por defecto
        if ($_POST["id_color"] == "") {
            $_POST["id_color"] = "1";
        }

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $datos["id_rol"] = (string) $_SESSION["usuario"]["id_rol"];
        $data["idUsuario"] = $idUsuario;

        $data["datos"] = $datos;
        $data["modoEdit"] = true;

        $errores = $this->comprobarEdit($datos);

        if (empty($errores)) {
            if (!empty($_SESSION["usuario"]) && $_SESSION["usuario"]["id_usuario"] == $idUsuario) {
// Si está vacío se actualiza el usuario sin cambiar la contraseña
                if (empty($datos["contrasena"])) {
                    if ($modeloUsuario->editUsuario($datos["username"], null, $datos["email"], $datos["id_rol"], $datos["fecha_nacimiento"], $datos["descripcion_usuario"], $datos["id_color"], $idUsuario)) {
                        if ($_SESSION["usuario"]["id_rol"] == 1) {
                            header("location: /admin/usuarios");
                        } else {
                            header("location: /perfil/" . $idUsuario);
                        }
                    }
                } else if ($modeloUsuario->editUsuario($datos["username"], $datos["contrasena"], $datos["email"], $datos["id_rol"], $datos["fecha_nacimiento"], $datos["descripcion_usuario"], $datos["id_color"], $idUsuario)) {
                    if ($_SESSION["usuario"]["id_rol"] == 1) {
                        header("location: /admin/usuarios");
                    } else {
                        header("location: /perfil/" . $idUsuario);
                    }
                }
            } else {
                header("location : /");
            }
        } else {
            $modeloRol = new \Com\TaskVelocity\Models\RolModel();
            $data["roles"] = $modeloRol->mostrarRoles();

            $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            $data["errores"] = $errores;

            if ($_SESSION["usuario"]["id_rol"] == 1) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->showViews(array('public/editar.perfil.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        }
    }

    public function verUsuario(int $idUsuario): void {
        $data = [];
        $data['titulo'] = 'Ver usuario con el id ' . $idUsuario;
        $data['seccion'] = '/admin/usuarios/view/' . $idUsuario;
        $data['tituloDiv'] = 'Ver usuario';

        $modeloRol = new \Com\TaskVelocity\Models\RolModel();
        $data["roles"] = $modeloRol->mostrarRoles();

        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data["datos"] = $modeloUsuario->buscarUsuarioPorId($idUsuario);
        $data["modoVer"] = true;

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    public function procesarDelete(int $idUsuario): void {
        $data = [];

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

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

    public function mostrarPerfil(int $idUsuario): void {
        $data = [];
        $data['titulo'] = 'Tu perfil';
        $data['seccion'] = '/perfil/' . $idUsuario;

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data['usuario'] = $modeloUsuario->buscarUsuarioPorId($idUsuario);

        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $data["proyectoPropietario"] = $modeloProyecto->contadorPorUsuarioPropietario($idUsuario);

        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
        $data["tareaPropietario"] = $modeloTarea->contadorPorUsuarioPropietario($idUsuario);

        $data["tareasPendientes"] = $modeloTarea->contadorTareasPorEtiqueta("1");
        $data["tareasProgresos"] = $modeloTarea->contadorTareasPorEtiqueta("2");
        $data["tareasFinalizadas"] = $modeloTarea->contadorTareasPorEtiqueta("3");

        $modeloEtiqueta = new \Com\TaskVelocity\Models\EtiquetaModel();
        $data["etiquetas"] = $modeloEtiqueta->mostrarEtiquetas();
        
        $data["idUsuario"] = $idUsuario;

        $this->view->showViews(array('public/perfil.view.php', 'public/plantillas/footer.view.php'), $data);
    }

    public function mostrarPerfilEditar(int $idUsuario): void {
        $data = [];
        $data['titulo'] = 'Tu perfil';
        $data['seccion'] = '/perfil/editar/' . $idUsuario;
        $data["tituloDiv"] = "Editando tus datos";

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data['datos'] = $modeloUsuario->buscarUsuarioPorId($idUsuario);

        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $data["idUsuario"] = $idUsuario;
        $data["modoEdit"] = true;

        if (!empty($_SESSION["usuario"]) && $_SESSION["usuario"]["id_usuario"] == $idUsuario) {
            $this->view->showViews(array('public/editar.perfil.view.php', 'public/plantillas/footer.view.php'), $data);
        } else {
            header("location: /");
        }
    }

    private function comprobarComun(array $data): array {
        $errores = [];

        $modeloRol = new \Com\TaskVelocity\Models\RolModel();
        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $dimensionesAvatar = 256;

        if (empty($data["username"])) {
            $errores["username"] = "El nombre de usuario no debe estar vacío";
        } else if (!preg_match("/^[a-z0-9]{4,}$/", $data["username"])) {
            $errores["username"] = "El nombre de usuario no cumple los mínimos. Mínimo 4 caracteres (letras y numeros)";
        }

        if (!empty($_FILES["imagen_avatar"]["name"])) {
            if ($_FILES["imagen_avatar"]["type"] != "image/jpeg" && $_FILES["imagen_avatar"]["type"] != "image/png") {
                $errores["imagen_avatar"] = "Tipo de imagen no aceptado";
            } else if (getimagesize($_FILES["imagen_avatar"]["tmp_name"])[0] > $dimensionesAvatar || getimagesize($_FILES["imagen_avatar"]["tmp_name"])[1] > $dimensionesAvatar) {
                $errores["imagen_avatar"] = "Dimensiones de imagen no válidas. Las dimensiones máximas son 256 x 256";
            } else if ($_FILES["imagen_avatar"]["size"] > 10 * self::MB) {
                $errores["imagen_avatar"] = "Imagen demasiada pesada";
            }
        }

        if (empty($data["email"])) {
            $errores["email"] = "El email no debe estar vacío";
        } else if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            $errores["email"] = "El email debe ser un email válido";
        }

        if (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["id_usuario"] == 1) {
            if (empty($data["id_rol"])) {
                $errores["id_rol"] = "Debes seleccionar un rol";
            } else if (!filter_var($data["id_rol"], FILTER_VALIDATE_INT)) {
                $errores["id_rol"] = "El rol debe ser un número";
            } else if (!$modeloRol->comprobarRol($data["id_rol"])) {
                $errores["id_rol"] = "Debes seleccionar un rol válido";
            }
        }

        if (empty($data["fecha_nacimiento"])) {
            $errores["fecha_nacimiento"] = "La fecha de nacimiento no debe estar vacía";
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

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        if ($data["username"] != $_SESSION["usuario"]["username"] && !is_null($modeloUsuario->buscarUsuarioPorUsername($data["username"]))) {
            $errores["username"] = "El nombre de usuario ya existe";
        }

        if ($data["email"] != $_SESSION["usuario"]["email"] && !is_null($modeloUsuario->buscarUsuarioPorEmail($data["email"]))) {
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

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        if (!is_null($modeloUsuario->buscarUsuarioPorUsername($data["username"]))) {
            $errores["username"] = "El nombre de usuario ya existe";
        }

        if (!is_null($modeloUsuario->buscarUsuarioPorEmail($data["email"])) && $data["email"] != $modeloUsuario->buscarUsuarioPorEmail($data["email"])["email"]) {
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
            "logs" => "",
        );

        switch ($idRol) {
            case 1:
                $permisos["inicio"] = "rwd";
                $permisos["usuarios"] = "rwd";
                $permisos["tareas"] = "rwd";
                $permisos["proyectos"] = "rwd";
                $permisos["logs"] = "rwd";
                break;
            case 2:
                $permisos["inicio"] = "";
                $permisos["usuarios"] = "";
                $permisos["tareas"] = "";
                $permisos["proyectos"] = "";
                $permisos["logs"] = "";
                break;
        }

        return $permisos;
    }
}
