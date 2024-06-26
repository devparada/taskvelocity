<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Controllers;

class UsuarioController extends \Com\TaskVelocity\Core\BaseController {

    /**
     * El id del rol del admin
     */
    public const ROL_ADMIN = 1;

    /**
     * El id del rol del usuario
     */
    public const ROL_USUARIO = 2;

    public function mostrarUsuarios() {
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $modeloRol = new \Com\TaskVelocity\Models\RolModel();

        if (empty($_GET["pagina"])) {
            $_GET["pagina"] = 0;
        }

        $data = [
            "titulo" => "Todos los usuarios",
            "seccion" => "/admin/usuarios",
            "paginaActual" => $_GET["pagina"],
            "maxPagina" => $modeloUsuario->obtenerPaginas(),
            "usuarios" => $modeloUsuario->mostrarUsuarios((int) $_GET["pagina"]++),
            "roles" => $modeloRol->mostrarRoles(),
            "contarUsuarios" => $modeloUsuario->contador()
        ];

        if (!empty($_GET["id_rol"])) {
            $data["usuarios"] = $modeloUsuario->filtrarPorRol((int) $_GET["id_rol"], (int) $data["paginaActual"]);
        }

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    public function buscarUsuariosAsync() {
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        echo json_encode($modeloUsuario->buscarUsuariosAsync());
    }

    public function obtenerPaginas(): float {
        $numeroPaginas = ceil($this->contador() / $_ENV["tabla.filasPagina"]);

        return $numeroPaginas;
    }

    public function mostrarLogin() {
        $data = [
            "titulo" => "Login"
        ];

        $this->view->show('public/login.view.php', $data);
    }

    public function procesarLogin(): void {
        $data = [
            "titulo" => "Login"
        ];

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        if ($modeloUsuario->procesarLogin($datos["emailUsername"], $datos["password"]) && !empty($datos["emailUsername"]) && !empty($datos["password"])) {
            $this->crearLogin($datos["emailUsername"]);
        } else {
            $data["loginError"] = "Datos incorrectos";
            $this->view->show('public/login.view.php', $data);
        }
    }

    private function crearLogin(string $emailUsername): void {
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        if (str_contains($emailUsername, "@")) {
            $usuarioEncontrado = $modeloUsuario->buscarUsuarioPorEmail($emailUsername);
        } else {
            $usuarioEncontrado = $modeloUsuario->buscarUsuarioPorUsername($emailUsername);
        }

        $modeloLog = new \Com\TaskVelocity\Models\LogModel();
        $modeloLog->crearLog("El usuario " . $usuarioEncontrado["username"] . " ha iniciado sesión", self::ROL_ADMIN);

        $_SESSION["usuario"] = $usuarioEncontrado;
        $_SESSION["permisos"] = $this->verPermisos($usuarioEncontrado["id_rol"]);

        if ($usuarioEncontrado["id_rol"] == self::ROL_ADMIN) {
            header("location: /admin");
        } else {
            // $_SESSION["historial] almacena la URL visitada anterior
            if (!empty($_SESSION["historial"])) {
                header("location: " . $_SESSION["historial"]);
            } else {
                header("location: /proyectos");
            }
        }
    }

    public function mostrarRegister() {
        $modeloColores = new \Com\TaskVelocity\Models\ColorModel();

        $data = [
            "titulo" => "Register",
            "colores" => $modeloColores->mostrarColores()
        ];

        $this->view->show('public/register.view.php', $data);
    }

    public function mostrarAddUsuario() {
        $modeloRol = new \Com\TaskVelocity\Models\RolModel();
        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();

        $data = [
            "titulo" => "Añadir usuario",
            "seccion" => "/admin/usuarios/add",
            "tituloDiv" => "Añadir usuario",
            "roles" => $modeloRol->mostrarRoles(),
            "colores" => $modeloColor->mostrarColores()
        ];

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    /**
     * Este método procesa añadir un usuario a partir de los datos recibidos de un formulario
     * de añadir usuario (tiene en cuenta el id_rol) o register (no tiene en cuenta el id_rol) y
     * Cuando el usuario se registra, inicia sesión automáticamente.
     * @return void No devuelve nada
     */
    public function procesarRegister(): void {
        if (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN) {
            $data = [
                "titulo" => "Añadir usuario",
                "seccion" => "/admin/usuarios/add",
                "tituloDiv" => "Añadir usuario"
            ];
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
            $modeloLog = new \Com\TaskVelocity\Models\LogModel();
            if (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["id_rol"] = self::ROL_ADMIN) {
                if ($modeloUsuario->addUsuario($datos["username"], $datos["contrasena"], $datos["email"], $datos["id_rol"], $datos["fecha_nacimiento"], $datos["descripcion_usuario"], $datos["id_color"])) {
                    $modeloLog->crearLog("Se ha creado el usuario " . $datos["username"], self::ROL_ADMIN);
                    header("location: /admin/usuarios");
                }
            } else {
                // El 2 es el id de rol del usuario (cuando se registra el usuario se añade el id de rol 2 que es usuario)
                if ($modeloUsuario->addUsuario($datos["username"], $datos["contrasena"], $datos["email"], self::ROL_USUARIO, null, "", $datos["id_color"])) {
                    $this->crearLogin($datos["email"]);
                    $modeloLog->crearLog("Se ha registrado el usuario " . $datos["username"], self::ROL_ADMIN);
                    header("location: /proyectos");
                }
            }
        } else {
            $data["titulo"] = "Register";
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

        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN) {
            $data = [
                "titulo" => "Editar usuario con el id " . $idUsuario,
                "seccion" => "/admin/usuarios/edit/" . $idUsuario,
                "tituloDiv" => "Editar usuario",
                "titulo" => "Editando tu perfil"
            ];
        } else {
            $data = [
                "titulo" => "Editando tu perfil",
                "seccion" => "/perfil/editar/" . $idUsuario
            ];
        }


        $modeloRol = new \Com\TaskVelocity\Models\RolModel();
        $data["roles"] = $modeloRol->mostrarRoles();

        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
        $data["colores"] = $modeloColor->mostrarColores();

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $data["datos"] = $modeloUsuario->buscarUsuarioPorId($idUsuario);

        $data["idUsuario"] = $idUsuario;
        $data["enviar"] = "Guardar cambios";
        $data["modoEdit"] = true;

        if (!empty($_SESSION["usuario"]) && ($_SESSION["usuario"]["id_usuario"] == $idUsuario || $_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN)) {
            if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->showViews(array('public/editar.perfil.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        } else {
            header("location: /");
        }
    }

    /**
     * Edita el usuario según los cambios que pase el usuario
     * @param int $idUsuario el id del usuario a editar
     * @return void
     */
    public function procesarEdit(int $idUsuario): void {
        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN) {
            $data = [
                "titulo" => "Editar usuario con el id " . $idUsuario,
                "seccion" => "/admin/usuarios/edit/" . $idUsuario,
                "tituloDiv" => "Editar usuario"
            ];
        } else {
            $data = [
                "titulo" => "Tu perfil",
                "seccion" => "/perfil/editar/" . $idUsuario,
            ];
        }

        unset($_POST["enviar"]);

        // Si id_color está vacio se añade el 1 que es el color por defecto
        if ($_POST["id_color"] == "") {
            $_POST["id_color"] = "1";
        }

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $datos = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        if (!array_key_exists("id_rol", $datos)) {
            $datos["id_rol"] = (string) $_SESSION["usuario"]["id_rol"];
        }

        $data["idUsuario"] = $idUsuario;
        $data["datos"] = $datos;
        $data["modoEdit"] = true;

        $errores = $this->comprobarEdit($datos, $idUsuario);

        if (empty($errores)) {
            if (!empty($_SESSION["usuario"]) && ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN || $_SESSION["usuario"]["id_usuario"] == $idUsuario)) {
                // Si está vacío se actualiza el usuario sin cambiar la contraseña
                if (empty($datos["contrasena"])) {
                    if ($modeloUsuario->editUsuario($datos["username"], null, $datos["email"], $datos["id_rol"], $datos["fecha_nacimiento"], $datos["descripcion_usuario"], $datos["id_color"], $idUsuario)) {
                        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN) {
                            header("location: /admin/usuarios");
                        } else {
                            header("location: /perfil/" . $idUsuario);
                        }
                    }
                } else if ($modeloUsuario->editUsuario($datos["username"], $datos["contrasena"], $datos["email"], $datos["id_rol"], $datos["fecha_nacimiento"], $datos["descripcion_usuario"], $datos["id_color"], $idUsuario)) {
                    if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN) {
                        header("location: /admin/usuarios");
                    } else {
                        header("location: /perfil/" . $idUsuario);
                    }
                }
            } else {
                header("location: /");
            }
        } else {
            $modeloRol = new \Com\TaskVelocity\Models\RolModel();
            $data["roles"] = $modeloRol->mostrarRoles();

            $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
            $data["colores"] = $modeloColor->mostrarColores();

            $data["errores"] = $errores;
            $data["datos"] = $modeloUsuario->buscarUsuarioPorId($idUsuario);
            $data["enviar"] = "Confirmar cambios";

            if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN) {
                $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
            } else {
                $this->view->showViews(array('public/editar.perfil.view.php', 'public/plantillas/footer.view.php'), $data);
            }
        }
    }

    public function verUsuarioAdmin(int $idUsuario): void {
        $modeloRol = new \Com\TaskVelocity\Models\RolModel();
        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $data = [
            "titulo" => "Ver usuario " . $idUsuario,
            "seccion" => "/admin/usuarios/view/" . $idUsuario,
            "tituloDiv" => "Mostrando los datos del usuario " . $idUsuario,
            "roles" => $modeloRol->mostrarRoles(),
            "colores" => $modeloColor->mostrarColores(),
            "datos" => $modeloUsuario->buscarUsuarioPorId($idUsuario),
            "idUsuario" => $idUsuario,
            "modoVer" => true
        ];

        $this->view->showViews(array('admin/templates/header.view.php', 'admin/add.usuario.view.php', 'admin/templates/footer.view.php'), $data);
    }

    public function procesarDelete(int $idUsuario): void {
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $usuarioEncontrado = $modeloUsuario->buscarUsuarioPorId($idUsuario);

        if (!is_null($usuarioEncontrado)) {
            $nombreUsuario = $usuarioEncontrado["username"];
        } else {
            $nombreUsuario = "con el id " . $idUsuario;
        }

        if (empty($_GET["pagina"])) {
            $_GET["pagina"] = 0;
        }

        $data = [
            "titulo" => "Todos los usuarios",
            "seccion" => '/admin/usuarios',
            "usuarios" => $modeloUsuario->mostrarUsuarios(),
            "paginaActual" => $_GET["pagina"],
            "maxPagina" => $modeloUsuario->obtenerPaginas(),
            "usuarios" => $modeloUsuario->mostrarUsuarios((int) $_GET["pagina"]++),
            "contarProyectos" => $modeloUsuario->contador()
        ];

        if ($modeloUsuario->deleteUsuario($idUsuario)) {
            $data["informacion"]["estado"] = "success";
            $data["informacion"]["texto"] = "El usuario ha sido eliminado correctamente";
        } else {
            $data["informacion"]["estado"] = "danger";
            $data["informacion"]["texto"] = "El usuario no ha sido eliminado correctamente";
        }

        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN) {
            $data["usuarios"] = $modeloUsuario->mostrarUsuarios();
            $this->view->showViews(array('admin/templates/header.view.php', 'admin/usuario.view.php', 'admin/templates/footer.view.php'), $data);
        } else {
            session_destroy();
            session_start();
            header("location: /");
        }
    }

    public function mostrarPerfil(int $idUsuario): void {
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
        $modeloEtiqueta = new \Com\TaskVelocity\Models\EtiquetaModel();

        $data = [
            "seccion" => "/perfil/" . $idUsuario,
            "usuario" => $modeloUsuario->buscarUsuarioPorId($idUsuario),
            "proyectoPropietario" => $modeloProyecto->contadorPorUsuarioPropietario($idUsuario),
            "tareaPropietario" => $modeloTarea->contadorPorUsuarioPropietario($idUsuario),
            "tareasPendientes" => $modeloTarea->contadorTareasPorEtiqueta(1, $idUsuario),
            "tareasProgresos" => $modeloTarea->contadorTareasPorEtiqueta(2, $idUsuario),
            "tareasFinalizadas" => $modeloTarea->contadorTareasPorEtiqueta(3, $idUsuario),
            "etiquetas" => $modeloEtiqueta->mostrarEtiquetas(),
            "idUsuario" => $idUsuario
        ];

        $data["meses"] = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre'
        ];

        if ($_SESSION["usuario"]["id_usuario"] == $idUsuario) {
            $data["titulo"] = "Tu perfil";
        } else {
            $data["titulo"] = "Perfil de " . $data["usuario"]["username"];
        }

        $usuarioEncontrado = $modeloUsuario->buscarUsuarioPorId($idUsuario);
        if ($usuarioEncontrado["id_rol"] == self::ROL_ADMIN) {
            $this->view->showViews(array('public/proyectos.view.php', 'public/plantillas/footer.view.php'), $data);
        } else {
            $this->view->showViews(array('public/perfil.view.php', 'public/plantillas/footer.view.php'), $data);
        }
    }

    private function comprobarComun(array $data): array {
        $errores = [];

        $modeloRol = new \Com\TaskVelocity\Models\RolModel();
        $modeloColor = new \Com\TaskVelocity\Models\ColorModel();

        if (empty($data["username"])) {
            $errores["username"] = "El nombre de usuario no debe estar vacío";
        } else if (!preg_match("/^[a-zA-Z0-9]{4,32}$/", $data["username"])) {
            $errores["username"] = "El nombre de usuario no cumple los mínimos. Mínimo 4 caracteres y máximo 32 caracteres";
        }

        if (!empty($_FILES["imagen_avatar"]["name"])) {
            if ($_FILES["imagen_avatar"]["type"] == "image/gif") {
                $errores["imagen_avatar"] = "Tipo de imagen no aceptado";
            } else if ($_FILES["imagen_avatar"]["size"] > 10 * \Com\TaskVelocity\Models\FileModel::MB) {
                $errores["imagen_avatar"] = "Imagen demasiada pesada";
            }
        }

        if (empty($data["email"])) {
            $errores["email"] = "El email no debe estar vacío";
        } else if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            $errores["email"] = "El email debe ser un email válido";
        }

        if (isset($_SESSION["usuario"]) && $_SESSION["usuario"]["id_usuario"] == self::ROL_ADMIN) {
            if (empty($data["id_rol"])) {
                $errores["id_rol"] = "Debes seleccionar un rol";
            } else if (!filter_var($data["id_rol"], FILTER_VALIDATE_INT)) {
                $errores["id_rol"] = "El rol debe ser un número";
            } else if (!$modeloRol->comprobarRol($data["id_rol"])) {
                $errores["id_rol"] = "Debes seleccionar un rol válido";
            }
        }

        if (!empty($data["fecha_nacimiento"]) && !preg_match("/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/", $data["fecha_nacimiento"])) {
            $errores["fecha_nacimiento"] = "La fecha de nacimiento no tiene un formato válido. Ejemplo: 2024-04-09";
        }

        if (!$modeloColor->comprobarColorNumero($data["id_color"])) {
            $errores["id_color"] = "El color debe ser un número";
        } else if (!empty($data["id_color"]) && !$modeloColor->comprobarColor($data["id_color"])) {
            $errores["id_color"] = "Debes seleccionar un color válido";
        }

        if (!empty($data["descripcion_usuario"]) && strlen($data["descripcion_usuario"]) > 255) {
            $errores["descripcion_usuario"] = "La descripción es muy larga";
        }

        return $errores;
    }

    private function comprobarEdit(array $data, int $idUsuario): array {
        $errores = $this->comprobarComun($data);

        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $usuarioEncontrado = $modeloUsuario->buscarUsuarioPorId($idUsuario);

        if ($data["username"] != $usuarioEncontrado["username"] && !is_null($modeloUsuario->buscarUsuarioPorUsername($data["username"]))) {
            $errores["username"] = "El nombre de usuario ya existe";
        }

        if ($data["email"] != $usuarioEncontrado["email"] && !is_null($modeloUsuario->buscarUsuarioPorEmail($data["email"]))) {
            $errores["email"] = "El email ya existe";
        }

        if (!empty($data["contrasena"]) && !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[A-Za-z0-9]{8,64}$/", $data["contrasena"])) {
            $errores["contrasena"] = "La contraseña no cumple los mínimos. Tiene que contener 1 letra mayúscula, 1 minúscula y 1 número. Mínimo 8 caracteres y máximo 64 caracteres";
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
            // Expresión regular que obliga a que haya 1 mayúscula, 1 minúscula, 1 número, y 8 caracteres como mínimo. Máximo 15 caracteres.
        } else if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[A-Za-z0-9!@#\$%\^&\*\(\)_\-+=\[\]\{\};':\"\\\\|,.<>\/?`~]{8,15}$/", $data["contrasena"])) {
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
        switch ($idRol) {
            case self::ROL_ADMIN:
                $permisos = [
                    "inicio" => "rwd",
                    "usuarios" => "rwd",
                    "tareas" => "rwd",
                    "proyectos" => "rwd",
                    "logs" => "rwd",
                ];
                break;
            // Por defecto los permisos son restringidos en la vista administración
            default:
                $permisos = [
                    "inicio" => "",
                    "usuarios" => "",
                    "tareas" => "",
                    "proyectos" => "",
                    "logs" => "",
                ];
                break;
        }

        return $permisos;
    }
}
